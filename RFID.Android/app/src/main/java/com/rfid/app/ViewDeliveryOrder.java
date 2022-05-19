package com.rfid.app;

import static android.content.ContentValues.TAG;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ListView;

import androidx.appcompat.app.AppCompatActivity;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.VolleyLog;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.Volley;
import com.rfid.app.data.DeliveryOrder;
import com.rfid.app.data.DeliveryOrderAdapter;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

public class ViewDeliveryOrder extends AppCompatActivity implements SwipeRefreshLayout.OnRefreshListener {
 public static final String URLSELECT = "http://172.20.10.3/Retail/select.php";
//public static final String URLSELECT = "http://10.0.0.2/Retail/select.php";

    ListView list;
    SwipeRefreshLayout swipe;
    List<DeliveryOrder> itemList = new ArrayList<DeliveryOrder>();
    DeliveryOrderAdapter adapter;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_view_delivery_order);

        swipe = (SwipeRefreshLayout) findViewById(R.id.swipe);
        list = (ListView) findViewById(R.id.list);

        adapter = new DeliveryOrderAdapter(ViewDeliveryOrder.this, itemList);
        list.setAdapter(adapter);

        swipe.setOnRefreshListener(this);

        swipe.post(new Runnable() {
                       @Override
                       public void run() {
                           swipe.setRefreshing(true);
                           itemList.clear();
                           adapter.notifyDataSetChanged();
                           callVolley();
                       }
                   }
        );

        list.setOnItemLongClickListener(new AdapterView.OnItemLongClickListener() {
            @Override
            public boolean onItemLongClick(AdapterView<?> parent, View view, int position, long id) {
                final String deliveryOrderid = itemList.get(position).getDelivery_Order_id();
                final String date = itemList.get(position).getDelivery_Order_date();
                final String status = itemList.get(position).getOrder_status();
                final String expQuan = itemList.get(position).getExpected_quantity();
                moveToScanActivity(deliveryOrderid, date, status, expQuan);

                return false;
            }
        });

    }

    public void moveToScanActivity(String deliveryOrderId, String date, String status, String expQuan){
        Intent in = new Intent(ViewDeliveryOrder.this, RFIDScanActivity.class);
        in.putExtra("delivery_Order_id", deliveryOrderId);
        in.putExtra("delivery_Order_date", date);
        in.putExtra("order_status", status);
        in.putExtra("expected_quantity", expQuan);
        startActivity(in);
        finish();
    }

    @Override
    public void onRefresh() {
        itemList.clear();
        adapter.notifyDataSetChanged();
        callVolley();

    }

    //get delivery order information
    private void callVolley() {
        itemList.clear();
        adapter.notifyDataSetChanged();
        swipe.setRefreshing(true);

        JsonArrayRequest jArr = new JsonArrayRequest(URLSELECT, new Response.Listener<JSONArray>() {
            @Override
            public void onResponse(JSONArray response) {
                // Parsing json
                for (int i = 0; i < response.length(); i++) {
                    try {
                        JSONObject obj = response.getJSONObject(i);

                        DeliveryOrder item = new DeliveryOrder();

                        item.setDelivery_Order_id(obj.getString("delivery_Order_id"));
                        item.setDelivery_Order_date(obj.getString("delivery_Order_date"));
                        item.setOrder_status(obj.getString("order_status"));
                        item.setExpected_quantity(obj.getString("expected_quantity"));
                        item.setActual_quantity(obj.getString("actual_quantity"));

                        itemList.add(item);
                    } catch (JSONException e) {
                        e.printStackTrace();
                    }
                }

                adapter.notifyDataSetChanged();

                swipe.setRefreshing(false);
            }
        }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                VolleyLog.d(TAG, "Error: " + error.getMessage());
                swipe.setRefreshing(false);
            }
        });


        RequestQueue mRequestQueue = Volley.newRequestQueue(getApplicationContext());
        mRequestQueue.add(jArr);

    }
}