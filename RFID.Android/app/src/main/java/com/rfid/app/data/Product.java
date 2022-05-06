package com.rfid.app.data;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.List;

public class Product implements Serializable{
    private String product_line_id, name;
    private List<Product> product = new ArrayList<Product>();
    public Product(){}
    public Product(String product_line_id, String name){
        this.product_line_id = product_line_id;
        this.name = name;
    }
    public String getProduct_line_id() {
        return product_line_id;
    }

    public void setProduct_line_id(String product_line_id) {
        this.product_line_id = product_line_id;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public List<Product> getProduct() {
        return product;
    }

    public void setProduct(List<Product> product) {
        this.product = product;
    }
    @Override
    public String toString() {
        return name;
    }
}
