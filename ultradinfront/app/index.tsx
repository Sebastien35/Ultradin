
import React, { useState, useEffect } from "react";
import { StyleSheet, Text, View, ScrollView, Alert } from "react-native";
import NavBar from "@/components/ui/navbar";
import { GetProducts } from "@/scripts/GetProducts";

export default function Home() {
    const [products, setProducts] = useState([]);
    const [error, setError] = useState("");

    const fetchProducts = async () => {
        const FetchProducts = await GetProducts();
        if (FetchProducts.status === "OK") {
            setProducts(FetchProducts.data);
        } else {
            setError("Error fetching products");
            console.error(FetchProducts.data);
        }
    };

    useEffect(() => {
        fetchProducts();
    }, []);

    return (
        <View style={styles.body}>
            <NavBar />
            <Text style={styles.title}>PAGE ACCUEIL</Text>
            {error ? (
                <Text style={styles.error}>{error}</Text>
            ) : (
                <ScrollView contentContainerStyle={styles.cardContainer}>
                    {products.map((product) => (
                        <View key={product.id} style={styles.card}>
                            <Text style={styles.cardTitle}>{product.name}</Text>
                            <Text style={styles.cardDescription}>
                                {product.description || "No description available."}
                            </Text>
                        </View>
                    ))}
                </ScrollView>
            )}
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
    error: {
        color: "red",
        textAlign: "center",
    },
    cardContainer: {
        paddingBottom: 20,
    },
    card: {
        backgroundColor: "#fff",
        borderRadius: 8,
        padding: 15,
        marginBottom: 10,
        shadowColor: "#000",
        shadowOpacity: 0.1,
        shadowOffset: { width: 0, height: 2 },
        shadowRadius: 4,
        elevation: 3, // Adds shadow for Android
    },
    cardTitle: {
        fontSize: 18,
        fontWeight: "bold",
        marginBottom: 5,
    },
    cardDescription: {
        fontSize: 14,
        color: "#555",
    },
});
