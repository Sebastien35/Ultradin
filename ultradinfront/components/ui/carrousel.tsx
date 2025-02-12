import React, { useState, useEffect, useRef } from "react";
import { View, Text, ScrollView, TouchableOpacity, StyleSheet, Animated } from "react-native";
import { GetProducts } from "@/scripts/GetProducts";
import { useRouter } from "expo-router";
import { Ionicons } from "@expo/vector-icons";

interface Product {
    id_product: number;
    name: string;
    description?: string;
}

export default function ProductCarousel() {
    const [products, setProducts] = useState<Product[]>([]);
    const [error, setError] = useState("");
    const scrollViewRef = useRef<ScrollView | null>(null);
    const [currentIndex, setCurrentIndex] = useState(0);

    const router = useRouter();
    const itemWidth = 220; // Largeur d'une carte + marge
    const visibleItems = 5; // Nombre d'éléments visibles
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

    const scrollRight = () => {
        if (scrollViewRef.current && currentIndex < totalItems - visibleItems) {
            const newIndex = currentIndex + 1;
            setCurrentIndex(newIndex);
            scrollViewRef.current.scrollTo({ x: newIndex * itemWidth, animated: true });
        }
    };

    const scrollLeft = () => {
        if (scrollViewRef.current && currentIndex > 0) {
            const newIndex = currentIndex - 1;
            setCurrentIndex(newIndex);
            scrollViewRef.current.scrollTo({ x: newIndex * itemWidth, animated: true });
        }
    };

    return (
        <View style={styles.container}>
            <Text style={styles.title}>Produits</Text>
            {error ? (
                <Text style={styles.error}>{error}</Text>
            ) : (
                <View style={styles.carouselWrapper}>
                    <TouchableOpacity onPress={scrollLeft} style={[styles.arrowButton, currentIndex === 0 && styles.disabledButton]}>
                        <Ionicons name="chevron-back" size={32} color={currentIndex === 0 ? "#ccc" : "black"} />
                    </TouchableOpacity>

                    <ScrollView
                        ref={scrollViewRef}
                        horizontal
                        showsHorizontalScrollIndicator={false}
                        scrollEnabled={true} // Désactivation du défilement manuel
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
                            </TouchableOpacity>
                        ))}
                    </ScrollView>

                    <TouchableOpacity onPress={scrollRight} style={[styles.arrowButton, currentIndex >= totalItems - visibleItems && styles.disabledButton]}>
                        <Ionicons name="chevron-forward" size={32} color={currentIndex >= totalItems - visibleItems ? "#ccc" : "black"} />
                    </TouchableOpacity>
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
    arrowButton: {
        padding: 10,
    },
    disabledButton: {
        opacity: 0.5,
    },
});
