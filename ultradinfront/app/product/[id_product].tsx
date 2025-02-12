import React, { useEffect, useState } from "react";
import { Text, View, StyleSheet, Image, ScrollView, Button, Alert } from "react-native";
import { useLocalSearchParams } from "expo-router";
import { GetProducts } from "@/scripts/GetProducts";
import { Dimensions } from "react-native";
import Navbar from "@/components/ui/navbar";
import Loader from "@/components/ui/loader";

export default function Product() {
    const { id_product } = useLocalSearchParams();
    const [product, setProduct] = useState<{
        id: number | null;
        name: string;
        description: string;
        price: number | null;
        image_url: string;
        categories: Array<string>; // Assuming categories as strings for simplicity
    }>({
        id: null,
        name: "",
        description: "",
        price: null,
        image_url: "",
        categories: [],
    });

    const [suggestions, setSuggestions] = useState<{
        id: number;
        name: string;
        price: number | null;
        image_url: string;
    }[]>([]);
    const [error, setError] = useState("");

    const fetchProduct = async (id: number) => {
        const FetchProduct = await GetProducts(id);
        if (FetchProduct.status === "OK") {
            const data = FetchProduct.data;
            setProduct({
                id: data.id,
                name: data.name,
                description: data.description,
                price: data.price,
                image_url: data.image_url,
                categories: data.categories || [],
            });
            setSuggestions(data.suggestions || []);
        } else {
            setError("Error fetching product");
        }
    };

    useEffect(() => {
        const productId = parseInt(Array.isArray(id_product) ? id_product[0] : id_product, 10);
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

    if (!product || !product.id) {
        return (
            <View style={styles.body}>
                <Loader />
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

                <View style={styles.detailsContainer}>
                    <Text style={styles.title}>{product.name}</Text>
                    <Text style={styles.price}>
                        {product.price ? `$${product.price.toFixed(2)}` : "Price not available"}
                    </Text>
                    <Text style={styles.description}>
                        {product.description || "No description available."}
                    </Text>
                </View>

                <View style={styles.actionsContainer}>
                    <Button
                        title="Subscribe now"
                        onPress={() => Alert.alert("Cart", `${product.name} added to cart`)}
                    />
                    <Button
                        title="Try Now"
                        color="orange"
                        onPress={() => Alert.alert("Buy Now", `Proceeding to buy ${product.name}`)}
                    />
                </View>
            </View>

            {/* Suggestions Section */}
            <Text style={styles.suggestionsTitle}>You might also like:</Text>
            <View style={styles.suggestionsContainer}>
                <ScrollView horizontal showsHorizontalScrollIndicator={false}>
                    {suggestions.map((suggestion) => (
                        <View key={suggestion.id} style={styles.suggestionCard}>
                            <Image
                                source={{ uri: suggestion.image_url }}
                                style={styles.suggestionImage}
                                resizeMode="cover"
                            />
                            <Text style={styles.suggestionName}>{suggestion.name}</Text>
                            <Text style={styles.suggestionPrice}>
                                {suggestion.price ? `$${suggestion.price.toFixed(2)}` : "Price not available"}
                            </Text>
                        </View>
                    ))}
                </ScrollView>
            </View>
        </ScrollView>
    );
}


const screenWidth = Dimensions.get("window").width; 
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
        fontSize: 30,
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
    suggestionsContainer: {
        marginTop: 20,
        padding: 10,
        display: "flex",
        flexDirection: "row",
        justifyContent: "space-evenly",
    },
    suggestionsTitle: {
        fontSize: 18,
        fontWeight: "bold",
        marginBottom: 10,
    },
    suggestionCard: {
        backgroundColor: "#FFF",
        borderRadius: 10,
        padding: 10,
        marginRight: 10,
        shadowColor: "#000",
        shadowOpacity: 0.1,
        shadowOffset: { width: 0, height: 2 },
        shadowRadius: 4,
        elevation: 3,
        width: screenWidth * 0.18, 
    },
    suggestionImage: {
        width: "100%",
        height: screenWidth * 0.1, // Dynamic height based on screen width
        borderRadius: 5,
        marginBottom: 5,
    },
    suggestionName: {
        fontSize: 14,
        fontWeight: "bold",
        textAlign: "center",
    },
    suggestionPrice: {
        fontSize: 12,
        color: "green",
        textAlign: "center",
        marginTop: 5,
    },
});
