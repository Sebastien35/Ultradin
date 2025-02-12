import React, { useState, useEffect } from "react";
import { StyleSheet, Text, View, ScrollView, TouchableOpacity } from "react-native";
import NavBar from "@/components/ui/navbar";
import { GetProducts } from "@/scripts/GetProducts";
import { useRouter } from "expo-router";
import Footer from "@/components/ui/footer";
import ProductCarousel from "@/components/ui/carrousel";

export default function Home() {
    const [products, setProducts] = useState<{ id_product: number; name: string; description?: string }[]>([]);
    const [error, setError] = useState("");
    const router = useRouter();

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
            <ProductCarousel />
            {error ? (
                <Text style={styles.error}>{error}</Text>
            ) : (
                <ScrollView contentContainerStyle={styles.cardContainer}>
                    {products.map((product) => (
                        <TouchableOpacity
                            key={product.id_product}
                            style={styles.card}
                            onPress={() => router.push(`/product/${product.id_product}`)}
                        >
                            <Text style={styles.cardTitle}>{product.name}</Text>
                            <Text style={styles.cardDescription}>
                                {product.description || "No description available."}
                            </Text>
                        </TouchableOpacity>
                    ))}
                </ScrollView>
            )}
            <Footer />
        </View>
    );
}

const styles = StyleSheet.create({
    body: {
        flex: 1,
        backgroundColor: "#F2F2F2",
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