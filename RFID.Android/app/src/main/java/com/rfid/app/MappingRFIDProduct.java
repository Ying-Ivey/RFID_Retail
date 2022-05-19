
package com.rfid.app;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.rfid.app.data.Product;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;


public class MappingRFIDProduct extends AppCompatActivity{
    private Spinner sp_product;
    private String strReceiveRFID, strRFID, stridname_removefirstlastchar, stridname_json;
    private String product_line_id, strRFIDReceive;
    private String[] strID, strname;
    EditText id;
    Button btSave, btReturn;
    public static final String URLINSERT = "http://172.20.10.3/Retail/insertProductInstance.php";

    //public static final String URLINSERT = "http://10.0.0.2/Retail/insertproductinstance.php";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_mapping_rfidproduct);

        id = findViewById(R.id.product_instance_id);
        btSave = findViewById(R.id.btSave);
        btReturn = findViewById(R.id.btReturn);
        sp_product = (Spinner) findViewById(R.id.sp_product);
        Intent intent = getIntent();

        //receive RFID from RFIDScanACtivity and
        strReceiveRFID = intent.getExtras().getString("RFID");
        //reformat RFID to display on the screen and call api
        strRFID = strReceiveRFID.substring(1, strReceiveRFID.length() - 1);
        String[] RFIDs = strRFID.split("-");
        strRFIDReceive = new String();
        for (int i = 0; i<RFIDs.length; i++ ){
            strRFIDReceive += RFIDs[i] + "\n";
        }
        id.setText(strRFIDReceive);
        //get list product id and and, split "{"product"} in string
        stridname_json = intent.getExtras().getString("ListProductIDAndName");
        Log.i("list", "[" + stridname_json + "]");
        stridname_removefirstlastchar = stridname_json.substring(1, stridname_json.length() - 1);
        //list id and name of product
        String[] splits = stridname_removefirstlastchar.split("\"products\":");

        try {
            JSONArray array = new JSONArray(splits[1]);
            //array ID and name from ScanActivity, get id to api and name when show on the screen easier
            strID = new String[array.length()];
            strname = new String[array.length()];
            for (int i = 0; i < array.length(); i++) {
                JSONObject object = array.getJSONObject(i);
                strID[i] = object.getString("product_line_id");
                strname[i] = object.getString("name");

            }
            setData(); //show product name in dropdown list

        } catch (JSONException e) {
            e.printStackTrace();
        }

        btSave.setOnClickListener(new View.OnClickListener() {
            public void onClick(View v) {
                //insert RFID - ID Product in mapping table
                saveProcess();

            }
        });
        btReturn.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View view) {
                Intent in = new Intent(MappingRFIDProduct.this, RFIDScanActivity.class);
                startActivity(in);
                finish();
            }
        });
    }

    private void setData() {
        ArrayList<Product> productList = new ArrayList<>();
        //Add products
        for (int i = 0; i < strID.length; i++) {
            productList.add(new Product(strID[i], strname[i]));
        }
        //fill data in spinner
        ArrayAdapter<Product> adapter = new ArrayAdapter<Product>(this, android.R.layout.simple_spinner_dropdown_item, productList);
        sp_product.setAdapter(adapter);
        sp_product.setSelection(0);

        sp_product.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {

            @Override
            public void onItemSelected(AdapterView<?> adapterView, View view, int i, long l) {
                Product product = (Product) adapterView.getSelectedItem();
                Toast.makeText(getApplicationContext(), "Product ID: " + product.getProduct_line_id() + ",  Product Name : " + product.getName(), Toast.LENGTH_SHORT).show();
                product_line_id = product.getProduct_line_id();
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {
            }
        });


    }

    public void saveProcess() {
        StringRequest stringRequest = new StringRequest(Request.Method.POST, URLINSERT,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        //Toast.makeText(MappingRFIDProduct.this, response, Toast.LENGTH_LONG).show();
                        Log.i("response", response);
                        UIHelper.ToastMessage(MappingRFIDProduct.this, response);
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(MappingRFIDProduct.this, "không kết nối được với máy chủ, hãy kiểm tra cài đặt kết nối của bạn", Toast.LENGTH_LONG).show();
            }
        }) {

            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                // Posting parameters ke post url
                Map<String, String> params = new HashMap<String, String>();

                params.put("product_instance_id", strRFID);
                params.put("product_line_id", product_line_id);
                return params;

            }

        };
        RequestQueue queue = Volley.newRequestQueue(getApplicationContext());
        queue.add(stringRequest);

    }

}
