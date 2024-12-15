import React from "react";
import { Link, useRouter, usePathname } from "expo-router";
import { View, Text, StyleSheet, TextInput } from "react-native";

const Navbar = () => {
    const router = useRouter();
    return (
        <nav style={styles.navbar}>
            {/* Logo */}
            <div style={styles.logo}>LOGO</div>

            {/* Search Bar */}
            <div style={styles.searchBar}>
                <input
                    type="text"
                    placeholder="Search Bar"
                    style={styles.searchInput}
                />
            </div>

            <div style={styles.links}>
                <Link href="/" style={styles.link}>
                    Home
                </Link>
                <Link href="/products" style={styles.link}>
                    Products
                </Link>
                <Link href="/contact" style={styles.link}>
                    Contact
                </Link>
                <Link href="/login" style={styles.link}>
                    Account
                </Link>
            </div>

        </nav>
    );
};

const styles = StyleSheet.create({
    navbar: {
        display: "flex",
        alignItems: "center",
        padding: "15px 20px",
        fontFamily: "Arial, sans-serif",
        backgroundColor: "white",
    },
    logo: {
        fontWeight: "bold",
        fontSize: "18px",
        marginRight: "4%",
    },
    searchBar: {
        margin: "0 20px",
        width: "20%",
        marginRight: "7%",
    },
    searchInput: {
        width: "100%",
        padding: "5px",
        border: "1px solid #ccc",
        borderRadius: "4px",
    },
    links: {
        display: "flex",
        gap: "15px",
    },
    link: {
        textDecoration: "none",
        color: "black",
        fontWeight: "500",
    },
    panier: {},
    button: {
        padding: "7px 15px",
        border: "1px solid black",
        borderRadius: "4px",
        backgroundColor: "white",
        cursor: "pointer",
        textDecoration: "none",
        color: "black",
    },
    rightNav: {
        width: "100%",
        display: "flex",
        alignItems: "center",
        justifyContent: "space-between",
    },
});

export default Navbar;
