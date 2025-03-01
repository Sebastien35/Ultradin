import React, {useContext} from "react";
import {AuthContext} from '@/app/Contexts/AuthContext'
import { Link, useRouter } from "expo-router";
import { View, Text, StyleSheet, TextInput, TouchableOpacity } from "react-native";
import App from '@/App'

const Navbar = () => {
    const router = useRouter();
    const {user, logout} = useContext(AuthContext);

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
                <TouchableOpacity onPress={() => router.push("/Search")}>
                    <Text style={styles.link}>Products</Text>
                </TouchableOpacity>
                {user ? (
                <>
                    <Text>Welcome, {user.username}!</Text>
                    <TouchableOpacity onPress={() => router.push("/logout")}></TouchableOpacity>
                </>
            ) : (
                <TouchableOpacity onPress={() => router.push("/login")}>
                    <Text style={styles.link}>Connexion</Text>
                </TouchableOpacity>
            )}
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
