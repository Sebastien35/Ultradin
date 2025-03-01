import React from "react";
import { Link, useRouter } from "expo-router";
import { View, Text, StyleSheet, TextInput, TouchableOpacity } from "react-native";

const Navbar = () => {
    const router = useRouter();

    return (
        <View style={styles.navbar}>
            {/* Logo */}
            <Text style={styles.logo}>LOGO</Text>

            {/* Search Bar */}
            <View style={styles.searchBar}>
                <TextInput
                    placeholder="Search Bar"
                    style={styles.searchInput}
                />
            </View>

            {/* Links */}
            <View style={styles.links}>
                <TouchableOpacity onPress={() => router.push("/")}>
                    <Text style={styles.link}>Home</Text>
                </TouchableOpacity>
                <TouchableOpacity onPress={() => router.push("/cart")}>
                    <Text style={styles.link}>
                        Cart  
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart-fill" viewBox="0 0 16 16">
                            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                        </svg>
                    </Text>
                </TouchableOpacity>
                <TouchableOpacity onPress={() => router.push("/Search")}>
                    <Text style={styles.link}>Products</Text>
                </TouchableOpacity>
                <TouchableOpacity onPress={() => router.push("/login")}>
                    <Text style={styles.link}>Account</Text>
                </TouchableOpacity>
            </View>
        </View>
    );
};

const styles = StyleSheet.create({
    navbar: {
        flexDirection: "row",
        alignItems: "center",
        paddingVertical: 15,
        paddingHorizontal: 20,
        backgroundColor: "white",
        justifyContent: "space-between",
    },
    logo: {
        fontWeight: "bold",
        fontSize: 18,
    },
    searchBar: {
        flex: 1,
        marginHorizontal: 15,
    },
    searchInput: {
        width: "100%",
        padding: 10,
        borderWidth: 1,
        borderColor: "#ccc",
        borderRadius: 4,
        fontSize: 16,
    },
    links: {
        flexDirection: "row",
        gap: 15,
    },
    link: {
        fontSize: 16,
        fontWeight: "500",
        color: "black",
    },
});

export default Navbar;
