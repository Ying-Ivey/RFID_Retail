package com.rfid.app.data;

public class DeliveryOrder {
    private String delivery_Order_id, delivery_Order_date, order_status, expected_quantity, actual_quantity;
    public DeliveryOrder(){}
    public DeliveryOrder(String delivery_Order_id, String delivery_Order_date, String order_status, String expected_quantity, String actual_quantity){
        this.delivery_Order_id = delivery_Order_id;
        this.delivery_Order_date = delivery_Order_date;
        this.order_status = order_status;
        this.expected_quantity = expected_quantity;
        this.actual_quantity = actual_quantity;
    }

    public String getDelivery_Order_id() {
        return delivery_Order_id;
    }

    public void setDelivery_Order_id(String delivery_Order_id) {
        this.delivery_Order_id = delivery_Order_id;
    }

    public String getDelivery_Order_date() {
        return delivery_Order_date;
    }

    public void setDelivery_Order_date(String delivery_Order_date) {
        this.delivery_Order_date = delivery_Order_date;
    }

    public String getOrder_status() {
        return order_status;
    }

    public void setOrder_status(String order_status) {
        this.order_status = order_status;
    }

    public String getExpected_quantity() {
        return expected_quantity;
    }

    public void setExpected_quantity(String expected_quantity) {
        this.expected_quantity = expected_quantity;
    }

    public String getActual_quantity() {
        return actual_quantity;
    }

    public void setActual_quantity(String actual_quantity) {
        this.actual_quantity = actual_quantity;
    }
}
