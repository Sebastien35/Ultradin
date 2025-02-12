import React, { useState, useEffect, useRef } from "react";
import { View, Text, ScrollView, TouchableOpacity, StyleSheet, Animated, Image } from "react-native";
import { GetProducts } from "@/scripts/GetProducts";
import { useRouter } from "expo-router";

interface Product {
    id_product: number;
    name: string;
    description?: string;
    price: number;
    image: string;
}

export default function ProductCarousel() {
    const [products, setProducts] = useState<Product[]>([]);
    const [error, setError] = useState("");
    const scrollViewRef = useRef<ScrollView | null>(null);

    const router = useRouter();
    const itemWidth = 220;
    const visibleItems = 5;
    const totalItems = products.length;

    useEffect(() => {
        const fetchProducts = async () => {
            const FetchProducts = await GetProducts();
            if (FetchProducts.status === "OK") {
                setProducts(FetchProducts.data);
            } else {
                setError("Error fetching products");
                console.error(FetchProducts.data);
            }
        };
        fetchProducts();
    }, []);

    return (
        <View style={styles.container}>
            {error ? (
                <Text style={styles.error}>{error}</Text>
            ) : (
                <View style={styles.carouselWrapper}>
                    <ScrollView
                        ref={scrollViewRef}
                        horizontal
                        showsHorizontalScrollIndicator={false}
                        scrollEnabled={false}
                        contentContainerStyle={styles.scrollContainer}
                    >
                        {products.map((product) => (
                            <TouchableOpacity
                                key={product.id_product}
                                style={styles.card}
                                onPress={() => router.push(`/product/${product.id_product}`)}
                            >
                                <Text style={styles.cardTitle}>{product.name}</Text>
                                <Text style={styles.cardDescription}>{product.description || "No description available."}</Text>
                                <Text style={styles.cardPrice}>{product.price}$</Text>
                            </TouchableOpacity>
                        ))}
                    </ScrollView>
                </View>
            )}
        </View>
    );
}

const styles = StyleSheet.create({
    container: {
        alignItems: "center",
        padding: 20,
    },
    title: {
        fontSize: 24,
        fontWeight: "bold",
        marginBottom: 10,
    },
    error: {
        color: "red",
    },
    carouselWrapper: {
        flexDirection: "row",
        alignItems: "center",
    },
    scrollContainer: {
        flexDirection: "row",
        paddingVertical: 10,
    },
    card: {
        width: 200,
        backgroundColor: "#fff",
        borderRadius: 8,
        padding: 15,
        marginHorizontal: 10,
        shadowColor: "#000",
        shadowOpacity: 0.1,
        shadowOffset: { width: 0, height: 2 },
        shadowRadius: 4,
        elevation: 3,
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
    cardPrice: {
        fontSize: 14,
        color: "#134ecd",
        position: "absolute",
        bottom: 15,
    },
    arrowLeft: {
        position: "absolute",
        left: 10,
        top: "50%",
        transform: [{ translateY: -12 }],
        backgroundColor: "rgba(0, 0, 0, 0.5)",
        padding: 10,
        borderRadius: 50,
    },
    arrowRight: {
        position: "absolute",
        right: 10,
        top: "50%",
        transform: [{ translateY: -12 }],
        backgroundColor: "rgba(0, 0, 0, 0.5)",
        padding: 10,
        borderRadius: 50,
    },
    disabledButton: {
        opacity: 0.5,
    },
});
