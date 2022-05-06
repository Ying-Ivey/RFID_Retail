package com.rfid.app;

import static android.content.ContentValues.TAG;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.os.Parcelable;
import android.text.TextUtils;
import android.util.Log;
import android.view.KeyEvent;
import android.view.View;
import android.widget.Button;
import android.widget.ListView;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.SimpleAdapter;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.VolleyLog;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.google.gson.Gson;
import com.rfid.app.data.Product;
import com.rscja.deviceapi.RFIDWithUHFUART;
import com.rscja.deviceapi.entity.UHFTAGInfo;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Set;
import java.util.stream.Collectors;

public class RFIDScanActivity extends Activity {
    private boolean loopFlag = false;
    private int inventoryFlag = 1;
    private Handler handler;
    private ArrayList<HashMap<String, String>> tagList;
    private SimpleAdapter adapter;

    private TextView tv_count;

    private RadioGroup RgInventory;
    private RadioButton RbInventorySingle;
    private RadioButton RbInventoryLoop;

    private Button BtClear;
    private Button BtImport;
    private Button BtInventory;
    private Button BtCheck;
    private Button BtMapping;

    private ListView LvTags;
    private HashMap<String, String> map;
    private RFIDWithUHFUART mReader;
    final ArrayList<Product> itemList = new ArrayList<Product>();

    private String fCurFilePath = "";
    private boolean fIsEmulator = false;

//    public static final String URLSELECTProductIDAndName = "http://172.20.10.2/Retail/selectproductidandname.php";
//    public static final String URLUPDATEDeliveryOrder = "http://172.20.10.2/Retail/checkrfidlist.php";
    public static final String URLSELECTProductIDAndName = "http://10.0.0.2/Retail/selectproductidandname.php";
    public static final String URLUPDATEDeliveryOrder = "http://10.0.0.2/Retail/checkrfidlist.php";

    String strID = "";
    String strDate = "";
    String strStatus = "";
    String strExpQuan = "";
    List<String> lstRFID;
    List<String> lstRFIDMapping;
    List<Product> list = new ArrayList<Product>();

    @SuppressLint("HandlerLeak")
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        try {
            super.onCreate(savedInstanceState);
            setContentView(R.layout.activity_rfid_scan);
            setTitle(getString(R.string.app_name) + " " + BuildConfig.VERSION_NAME);

            tagList = new ArrayList<HashMap<String, String>>();

            BtClear = (Button) findViewById(R.id.BtClear);
            BtImport = (Button) findViewById(R.id.BtImport);
            BtCheck = (Button) findViewById(R.id.BtCheck);
            BtMapping = (Button) findViewById(R.id.BtMapping);
            tv_count = (TextView) findViewById(R.id.tv_count);
            RgInventory = (RadioGroup) findViewById(R.id.RgInventory);
            RbInventorySingle = (RadioButton) findViewById(R.id.RbInventorySingle);
            RbInventoryLoop = (RadioButton) findViewById(R.id.RbInventoryLoop);
            BtInventory = (Button) findViewById(R.id.BtInventory);
            LvTags = (ListView) findViewById(R.id.LvTags);

            adapter = new SimpleAdapter(this, tagList, R.layout.listtag_items,
                    new String[]{"tagUii", "tagLen", "tagCount"},
                    new int[]{R.id.TvTagUii, R.id.TvTagLen, R.id.TvTagCount});

            BtClear.setOnClickListener(new BtClearClickListener());
            BtImport.setOnClickListener(new BtImportClickListener());
            RgInventory.setOnCheckedChangeListener(new RgInventoryCheckedListener());
            BtInventory.setOnClickListener(new BtInventoryClickListener());
            BtCheck.setOnClickListener(new BtCheckClickListener());
            BtMapping.setOnClickListener(new BtMappingClickListener());

            LvTags.setAdapter(adapter);
            clearData();

            handler = new Handler() {
                @SuppressLint("HandlerLeak")
                @Override
                public void handleMessage(Message msg) {
                    String result = msg.obj + "";
                    String[] strs = result.split("@");
                    if (addEPCToList(strs[0], strs[1]))
                        UIHelper.playSoundSuccess();
                }
            };

            fIsEmulator = UIHelper.isEmulator();
            UIHelper.initSound(RFIDScanActivity.this);
            initUHF();

            Intent intent = getIntent();
            if (intent.hasExtra("delivery_Order_id") && intent.hasExtra("delivery_Order_date") && intent.hasExtra("order_status") && intent.hasExtra("expected_quantity")) {
                strID = intent.getExtras().getString("delivery_Order_id");
                strDate = intent.getExtras().getString("delivery_Order_date");
                strStatus = intent.getExtras().getString("order_status");
                strExpQuan = intent.getExtras().getString("expected_quantity");
            }

            getProductIDAndName();
        } catch (Exception ex) {
            UIHelper.showExceptionError(RFIDScanActivity.this, ex);
        }
    }

    public void initUHF() {
        // temporary check this, on emulator device mReader InitTask cause crash application
        if (!fIsEmulator) {
            if (mReader == null) {
                try {
                    mReader = RFIDWithUHFUART.getInstance();
                } catch (Exception ex) {
                    UIHelper.showExceptionError(RFIDScanActivity.this, ex);
                    return;
                }

                if (mReader != null) {
                    new InitTask().execute();
                }
            }
        }
    }

    /**
     * @author liuruifeng
     */
    private class InitTask extends AsyncTask<String, Integer, Boolean> {
        ProgressDialog mypDialog;

        @Override
        protected Boolean doInBackground(String... params) {
            // TODO Auto-generated method stub
            try {
                return mReader.init();
            } catch (Exception ex) {
                return false;
            }
        }

        @Override
        protected void onPostExecute(Boolean result) {
            super.onPostExecute(result);

            mypDialog.cancel();

            if (!result) {
                Toast.makeText(RFIDScanActivity.this, "init fail", Toast.LENGTH_SHORT).show();
            }
        }

        @Override
        protected void onPreExecute() {
            // TODO Auto-generated method stub
            try {
                super.onPreExecute();

                mypDialog = new ProgressDialog(RFIDScanActivity.this);
                mypDialog.setProgressStyle(ProgressDialog.STYLE_SPINNER);
                mypDialog.setMessage("init...");
                mypDialog.setCanceledOnTouchOutside(false);
                mypDialog.show();

            } catch (Exception ex) {
                UIHelper.showExceptionError(RFIDScanActivity.this, ex);
                return;
            }
        }
    }

    @Override
    public void onPause() {
        super.onPause();

        stopInventory();
    }

    @Override
    public boolean onKeyDown(int keyCode, KeyEvent event) {
        if (keyCode == 139 || keyCode == 280 || keyCode == 293) {
            if (event.getRepeatCount() == 0) {
                readTag();
            }
            return true;
        }

        return super.onKeyDown(keyCode, event);
    }

    /**
     * @param epc
     */
    private boolean addEPCToList(String epc, String rssi) {
        if (!TextUtils.isEmpty(epc)) {
            int index = checkIsExist(epc);

            map = new HashMap<String, String>();
            map.put("tagUii", epc);
            map.put("tagCount", String.valueOf(1));
            map.put("tagRssi", rssi);

            if (index == -1) {
                tagList.add(map);
                LvTags.setAdapter(adapter);
                tv_count.setText("" + adapter.getCount());
            } else {
                int tagcount = Integer.parseInt(tagList.get(index).get("tagCount"), 10) + 1;

                map.put("tagCount", String.valueOf(tagcount));
                tagList.set(index, map);
            }

            adapter.notifyDataSetChanged();
            if (index >= 0)
                return false;

            return true;
        }
        return false;
    }

    private void getProductIDAndName(){

        // make JSON requests
        JsonArrayRequest jArr = new JsonArrayRequest(URLSELECTProductIDAndName, new Response.Listener<JSONArray>() {
            @Override
            public void onResponse(JSONArray response) {
                // Parsing json
                for (int i = 0; i < response.length(); i++) {
                    try {
                        Log.i("response", response.toString());
                        JSONObject obj = response.getJSONObject(i);

                        Product product = new Product();
                        product.setProduct_line_id(obj.getString("product_line_id"));

                        product.setName(obj.getString("name"));
                        list.add(product);

                    } catch (JSONException e) {
                        e.printStackTrace();
                    }
                }

            }
        }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                VolleyLog.d(TAG, "Error: " + error.getMessage());

            }
        });

        // add request to request queue
        RequestQueue mRequestQueue = Volley.newRequestQueue(getApplicationContext());
        mRequestQueue.add(jArr);

    }

    private class BtClearClickListener implements View.OnClickListener {

        @Override
        public void onClick(View v) {
            clearData();
        }
    }

    private class BtImportClickListener implements View.OnClickListener {
        @Override
        public void onClick(View v) {
            if (BtInventory.getText().equals(getString(R.string.btInventory))) {
                //remember uncomment this
//                if (tagList.size() == 0) {
//                    UIHelper.ToastMessage(RFIDScanActivity.this, "No data");
//                    return;
//                }

                try {
                    // save actual quantity
                    lstRFID = new ArrayList<String>();
                    lstRFIDMapping = new ArrayList<String>();
                    if(lstRFID.size()>0){
                        lstRFID.clear();
                    }
                    if(lstRFIDMapping.size()>0){
                        lstRFIDMapping.clear();
                    }
//                    lstRFID = getListRFID(tagList);
//                    lstRFIDMapping = getListRFID(tagList);

                    lstRFID.add("E280689020004003E24729E0");
                    lstRFID.add("E280689020005003E24711E0");
                    lstRFID.add("G300 1545 325G 0089 1132 4237");
                    lstRFID.add("U2G5 6677 34B3 1164 3367 2445");
                    lstRFIDMapping.add("E280689020004003E24729E0");

                    Log.i("tagLstRFID", "[" + lstRFID + "]");
                    Log.i("tagRFIDMapping", "[" + lstRFIDMapping + "]");
                    String temp = "";
                    for (int i = 0; i < lstRFID.size(); i++) {
                        Log.i("elementList", "[" + lstRFID.get(i) + "]");
                        saveProcessUpdateRFIDDeliveryOrder(lstRFID.get(i));
                    }

                    //clear tag list, return to original state
                    UIHelper.ToastMessage(RFIDScanActivity.this, getString(R.string.uhf_msg_inventory_save_success));
                    tv_count.setText("0");
                    tagList.clear();
                    adapter.notifyDataSetChanged();
                    lstRFID.clear();

                } catch (Exception ex) {
                    UIHelper.showExceptionError(RFIDScanActivity.this, ex);
                }
            } else {
                UIHelper.ToastMessage(RFIDScanActivity.this, R.string.uhf_msg_inventory_save_wanrning);
            }
        }
    }

    private class BtCheckClickListener implements View.OnClickListener {
        @Override
        public void onClick(View v) {
            if (BtInventory.getText().equals(getString(R.string.btInventory))) {
                Intent in = new Intent(RFIDScanActivity.this, ViewDeliveryOrder.class);
                startActivity(in);
                finish();
            } else {
                UIHelper.ToastMessage(RFIDScanActivity.this, R.string.uhf_msg_inventory_save_wanrning);
            }
        }
    }

    private class  BtMappingClickListener implements View.OnClickListener {
        @Override
        public void onClick(View v) {

            if (BtInventory.getText().equals(getString(R.string.btInventory))) {

                if(lstRFIDMapping.size()>0) {
                    String lstRFIDMappingToString = lstRFIDMapping.stream()
                            .map(n -> String.valueOf(n))
                            .collect(Collectors.joining("-", "{", "}"));
                    String[] partsRFID = lstRFIDMappingToString.split("-");
                    Log.i("lstRFIDMappingToString", "[" + lstRFIDMappingToString + "]");


                    if (partsRFID.length > 1) {
                        UIHelper.ToastMessage(RFIDScanActivity.this, R.string.listRFIDLengthMoreThanOne);
                    } else {
                        Intent in = new Intent(RFIDScanActivity.this, MappingRFIDProduct.class);
                        Gson gson = new Gson();
                        String intentData = "{\"products\":" + gson.toJson(list) + "}";
                        Log.i("list_intent", "[" + intentData + "]");
                        in.putExtra("RFID", lstRFIDMappingToString);
                        in.putExtra("ListProductIDAndName", intentData);

                        startActivity(in);
                        finish();
                    }
                }
            } else {
                UIHelper.ToastMessage(RFIDScanActivity.this, R.string.uhf_msg_inventory_save_wanrning);
            }
        }
    }

    private void clearData() {
        tv_count.setText("0");
        tagList.clear();

        adapter.notifyDataSetChanged();
    }

    public class RgInventoryCheckedListener implements RadioGroup.OnCheckedChangeListener {
        @Override
        public void onCheckedChanged(RadioGroup group, int checkedId) {
            if (checkedId == RbInventorySingle.getId()) {
                inventoryFlag = 0;
            } else if (checkedId == RbInventoryLoop.getId()) {
                inventoryFlag = 1;
            }
        }
    }

    public class BtInventoryClickListener implements View.OnClickListener {
        @Override
        public void onClick(View v) {
            readTag();
        }
    }

    private void readTag() {
        if (BtInventory.getText().equals(getString(R.string.btInventory))) {
            if (mReader == null) {
                UIHelper.ToastMessage(RFIDScanActivity.this, R.string.uhf_msg_sdk_open_fail);
                return;
            }

            switch (inventoryFlag) {
                case 0: {
                    UHFTAGInfo strUII = mReader.inventorySingleTag();
                    if (strUII != null) {
                        String strEPC = strUII.getEPC();
                        addEPCToList(strEPC, strUII.getRssi());
                        UIHelper.playSoundSuccess();
                        tv_count.setText("" + adapter.getCount());
                    } else {
                        UIHelper.ToastMessage(RFIDScanActivity.this, R.string.uhf_msg_inventory_fail);
                    }
                }
                break;
                case 1://  .startInventoryTag((byte) 0, (byte) 0))
                {
                    if (mReader.startInventoryTag()) {
                        BtInventory.setText(getString(R.string.title_stop_Inventory));
                        loopFlag = true;
                        setViewEnabled(false);
                        new TagThread().start();
                    } else {
                        mReader.stopInventory();
                        UIHelper.ToastMessage(RFIDScanActivity.this, R.string.uhf_msg_inventory_open_fail);
                    }
                }
                break;
                default:
                    break;
            }
        } else {
            stopInventory();
        }
    }

    private void setViewEnabled(boolean enabled) {
        RbInventorySingle.setEnabled(enabled);
        RbInventoryLoop.setEnabled(enabled);
        BtClear.setEnabled(enabled);
    }

    private void stopInventory() {
        if (loopFlag) {
            loopFlag = false;
            setViewEnabled(true);
            if (mReader.stopInventory()) {
                BtInventory.setText(getString(R.string.btInventory));
            } else {
                UIHelper.ToastMessage(RFIDScanActivity.this, R.string.uhf_msg_inventory_stop_fail);
            }
        }
    }

    /**
     * @param strEPC
     * @return
     */
    public int checkIsExist(String strEPC) {
        int existFlag = -1;
        if (strEPC == null || strEPC.length() == 0) {
            return existFlag;
        }
        String tempStr = "";
        for (int i = 0; i < tagList.size(); i++) {
            HashMap<String, String> temp = new HashMap<String, String>();
            temp = tagList.get(i);
            tempStr = temp.get("tagUii");
            if (strEPC.equals(tempStr)) {
                existFlag = i;
                break;
            }
        }
        return existFlag;
    }

    private class TagThread extends Thread {
        public void run() {
            String strTid;
            String strResult;
            UHFTAGInfo res = null;
            while (loopFlag) {
                res = mReader.readTagFromBuffer();
                if (res != null) {
                    strTid = res.getTid();
                    if (strTid.length() != 0 && !strTid.equals("0000000" + "000000000") && !strTid.equals("000000000000000000000000")) {
                        strResult = "TID:" + strTid + "\n";
                    } else {
                        strResult = "";
                    }

                    Message msg = handler.obtainMessage();
                    msg.obj = strResult + res.getEPC() + "@" + res.getRssi();

                    handler.sendMessage(msg);
                }
            }
        }
    }
    //convert arraylist hashmap RFID to list
    public List<String> getListRFID(ArrayList<HashMap<String, String>> lists2) {
        List<String> lst = new ArrayList<>();
        for (int i = 0; i < lists2.size(); i++) {
            Set<Map.Entry<String, String>> sets = lists2.get(i).entrySet();
            for (Map.Entry<String, String> entry : sets) {
                if (entry.getKey().equals("tagUii")) {
                    String str = entry.getValue().toString();
                    str = str.replace("EPC:", "");
                    str = str.replace("TID:", "");

                    lst.add(str);
                }
            }
        }
        return lst;
    }


    public void saveProcessUpdateRFIDDeliveryOrder(String tagRFID) {
        StringRequest stringRequest = new StringRequest(Request.Method.POST, URLUPDATEDeliveryOrder,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Log.i("response", response);

                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(RFIDScanActivity.this, "Failed to connect to the server, check your connection settings", Toast.LENGTH_LONG).show();
            }
        }) {

            @Override
            protected Map<String, String> getParams() throws AuthFailureError {

                Map<String, String> params = new HashMap<String, String>();
                params.put("delivery_Order_id", strID);
                params.put("product_instance_id", tagRFID);
                return params;

            }

        };
        RequestQueue queue = Volley.newRequestQueue(getApplicationContext());
        queue.add(stringRequest);

    }

}
