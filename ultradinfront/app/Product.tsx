import React from 'react';
import { Text, View, StyleSheet } from 'react-native';



export default function Product(idProduct: number) {
    return (
        <View style={styles.body}>
            <Text style={styles.title}>Product {idProduct}</Text>
        </View>
    )
}

const fetchProduct             = async () => {
    const FetchProducts = await GetProducts();
    if (FetchProducts.status === "OK") {
        setProducts(FetchProducts.data);
    } else {
        setError("Error fetching products");
        console.error(FetchProducts.data);
    }
};

const styles = StyleSheet.create({
    body: {
        flex: 1,
        backgroundColor: "#F2F2F2",
        paddingHorizontal: 10,
        paddingTop: 20,
    },
    title: {
        fontSize: 24,
        fontWeight: "bold",
        marginBottom: 20,
        textAlign: "center",
    },
    error: {
        color: "red",
        textAlign: "center",
    },
});