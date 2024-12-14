import React from "react";
import { View, Text, StyleSheet } from "react-native";
import NavBar from "@/components/ui/navbar";
import Navbar from "@/components/ui/navbar";

export default function NotFoundPage() {
    return (
        <body>
            <View>
                <Navbar/>
                <div style={styles.container}>
                    <Text style={styles.number}>404 </Text><br/>
                    <Text style={styles.title}>Page not found (Espèce de gros nul tu sais pas écrire)</Text>
                </div>
            </View>
        </body>
    );
}

const styles = StyleSheet.create({
    container: {
        padding: 150,
    },
    title: {
        fontSize: 35,
        fontWeight: "bold",
    },
    number: {
        fontSize: 120,
        fontWeight: "bold",
    },
});
