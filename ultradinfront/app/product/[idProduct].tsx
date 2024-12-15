import React, { useEffect, useState } from "react";
import { Text, View, StyleSheet } from "react-native";
import { useLocalSearchParams } from "expo-router";
import { GetProducts } from "@/scripts/GetProducts";

export default function Product() {
    const { idProduct } = useLocalSearchParams(); // Extract 'idProduct' from route parameters
    console.log(idProduct);
    const [product, setProduct] = useState<{ idProduct: number; name: string; description?: string } | null>(null);
    const [error, setError] = useState("");

    const fetchProduct = async (idProduct: number) => {
        const FetchProduct = await GetProducts(idProduct);
        if (FetchProduct.status === "OK") {
            setProduct(FetchProduct.data);
        } else {
            setError("Error fetching product");
            console.error(FetchProduct.data);
        }
    };

    useEffect(() => {
        fetchProduct(parseInt(idProduct));
    }, []);

    if (error) {
        return (
            <View style={styles.body}>
                <Text style={styles.error}>{error}</Text>
            </View>
        );
    }

    if (!product) {
        return (
            <View style={styles.body}>
                <Text style={styles.title}>Loading product...</Text>
            </View>
        );
    }

    return (
        <View style={styles.body}>
            <Text style={styles.title}>Product {product.idProduct}</Text>
            <Text style={styles.name}>{product.name} </Text>
            <Text style={styles.description}>
                {product.description || "No description available."}
            </Text>
        </View>
    );
}

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
    name: {
        fontSize: 18,
        fontWeight: "600",
        marginBottom: 10,
        textAlign: "center",
    },
    description: {
        fontSize: 16,
        color: "#555",
        textAlign: "center",
    },
    error: {
        color: "red",
        fontSize: 16,
        textAlign: "center",
    },
});
