package com.rfid.app.data;

import android.app.Activity;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

import com.rfid.app.R;

import java.util.List;

public class DeliveryOrderAdapter extends BaseAdapter {
    Activity activity;
    List<DeliveryOrder> items;
    private LayoutInflater inflater;

    public DeliveryOrderAdapter(Activity activity, List<DeliveryOrder> items) {
        this.activity = activity;
        this.items = items;
    }

    @Override
    public int getCount() {
        return items.size();
    }

    @Override
    public Object getItem(int position) {
        return items.get(position);
    }

    @Override
    public long getItemId(int position) {
        return position;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        if (inflater == null)
            inflater = (LayoutInflater) activity.getSystemService(Context.LAYOUT_INFLATER_SERVICE);

        if (convertView == null) convertView = inflater.inflate(R.layout.list_deliveryorder, null);

        TextView id = (TextView) convertView.findViewById(R.id.id);
        TextView date = (TextView) convertView.findViewById(R.id.date);
        TextView status = (TextView) convertView.findViewById(R.id.status);
        TextView sle = (TextView) convertView.findViewById(R.id.sle);
        TextView sla = (TextView) convertView.findViewById(R.id.sla);

        DeliveryOrder data = items.get(position);

        id.setText(data.getDelivery_Order_id());
        date.setText(data.getDelivery_Order_date());
        status.setText(data.getOrder_status());
        sle.setText(data.getExpected_quantity());
        sla.setText(data.getActual_quantity());

        return convertView;
    }
}
