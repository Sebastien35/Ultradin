import React, { useEffect, useState } from "react";
import { Text, View, StyleSheet, Image, ScrollView, Button, Alert } from "react-native";
import { useLocalSearchParams } from "expo-router";
import { GetProducts } from "@/scripts/GetProducts";
import Navbar from "@/components/ui/navbar";

export default function Product() {
    const { id_product } = useLocalSearchParams(); // Extract 'idProduct' from route parameters
    const [product, setProduct] = useState<{ 
        id_product: number; 
        name: string; 
        description?: string; 
        price?: number; 
        image_url?: string; 
    } | null>(null);
    const [error, setError] = useState("");

    const fetchProduct = async (id_product: number) => {
        const FetchProduct = await GetProducts(id_product);
        if (FetchProduct.status === "OK") {
            setProduct(FetchProduct.data);
        } else {
            setError("Error fetching product");
            console.error(FetchProduct.data);
        }
    };

    useEffect(() => {
        const productId = parseInt(id_product as string);
        if (!isNaN(productId)) {
            fetchProduct(productId);
        } else {
            setError("Invalid product ID");
        }
    }, [id_product]);

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
        <ScrollView style={styles.body}>
            <Navbar />
            <View style={styles.productContainer}>
                {product.image_url && (
                    <Image 
                        source={{ uri: product.image_url }} 
                        style={styles.productImage} 
                        resizeMode="contain" 
                    />
                )}

                {/* Product Details */}
                <View style={styles.detailsContainer}>
                    <Text style={styles.title}>{product.name}</Text>
                    <Text style={styles.price}>
                        {product.price ? `$${product.price.toFixed(2)}` : "Price not available"}
                    </Text>
                    <Text style={styles.description}>
                        {product.description || "No description available."}
                    </Text>
                </View>

                {/* Action Buttons */}
                <View style={styles.actionsContainer}>
                    <Button 
                        title="Add to Cart" 
                        onPress={() => Alert.alert("Cart", `${product.name} added to cart`)} 
                    />
                    <Button 
                        title="Buy Now" 
                        color="orange" 
                        onPress={() => Alert.alert("Buy Now", `Proceeding to buy ${product.name}`)} 
                    />
                </View>
            </View>
        </ScrollView>
    );
}

const styles = StyleSheet.create({
    body: {
        flex: 1,
        backgroundColor: "#F2F2F2",
    },
    productContainer: {
        backgroundColor: "#FFF",
        borderRadius: 10,
        padding: 15,
        marginBottom: 10,
        shadowColor: "#000",
        shadowOpacity: 0.1,
        shadowOffset: { width: 0, height: 2 },
        shadowRadius: 4,
        elevation: 3,
    },
    productImage: {
        width: "100%",
        height: 300,
        marginBottom: 15,
    },
    detailsContainer: {
        marginBottom: 15,
    },
    title: {
        fontSize: 22,
        fontWeight: "bold",
        marginBottom: 10,
        textAlign: "center",
    },
    price: {
        fontSize: 20,
        fontWeight: "600",
        color: "green",
        marginBottom: 10,
        textAlign: "center",
    },
    description: {
        fontSize: 16,
        color: "#555",
        marginBottom: 10,
        textAlign: "center",
    },
    actionsContainer: {
        flexDirection: "row",
        justifyContent: "space-around",
        marginTop: 15,
    },
    error: {
        color: "red",
        fontSize: 16,
        textAlign: "center",
    },
});
