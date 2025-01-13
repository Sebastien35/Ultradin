
import React, { useState } from "react";
import { TextInput, Button, StyleSheet, Text, View, Dimensions, Alert } from "react-native";
import { SafeAreaProvider, SafeAreaView } from "react-native-safe-area-context";
import NavBar from "@/components/ui/navbar";
import { API_URL } from "@/constants/Config";
import { Link } from "expo-router";

export default function Register() {
    const [email, setEmail] = useState("");
    const [confirmEmail, setConfirmEmail] = useState("");
    const [password, setPassword] = useState("");
    const [confirmPassword, setConfirmPassword] = useState("");
    const [phone, setPhone] = useState("");
    const [defaultPaymentMethod, setDefaultPaymentMethod] = useState("");

    const handleSubmit = async () => {
        if (email !== confirmEmail) {
            Alert.alert("Error", "Emails do not match");
            return;
        }
        if (password !== confirmPassword) {
            Alert.alert("Error", "Passwords do not match");
            return;
        }

        try {
            const response = await fetch(API_URL + "/register", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ email, password, phone, default_payment_method: defaultPaymentMethod }),
            });

            const data = await response.json();

            if (response.ok) {
                Alert.alert("Success", "Registration successful");
            } else {
                console.error("Registration failed:", data.error);
                Alert.alert("Error", data.error || "An error occurred");
            }
        } catch (error) {
            console.error(error);
            Alert.alert("Error", "An error occurred");
        }
    };

    return (
        <View style={styles.body}>
            <NavBar />
            <View style={styles.container}>
                <Text style={styles.title}>Register</Text>
                <TextInput
                    style={styles.input}
                    placeholder="Email"
                    value={email}
                    onChangeText={setEmail}
                />
                <TextInput
                    style={styles.input}
                    placeholder="Confirm Email"
                    value={confirmEmail}
                    onChangeText={setConfirmEmail}
                />
                <TextInput
                    style={styles.input}
                    placeholder="Password"
                    secureTextEntry
                    value={password}
                    onChangeText={setPassword}
                />
                <TextInput
                    style={styles.input}
                    placeholder="Confirm Password"
                    secureTextEntry
                    value={confirmPassword}
                    onChangeText={setConfirmPassword}
                />
                <TextInput
                    style={styles.input}
                    placeholder="Phone"
                    value={phone}
                    onChangeText={setPhone}
                    keyboardType="phone-pad"
                />
                <TextInput
                    style={styles.input}
                    placeholder="Default Payment Method"
                    value={defaultPaymentMethod}
                    onChangeText={setDefaultPaymentMethod}
                />
                <Button title="Submit" onPress={handleSubmit} />
                <Link href="/login" style={styles.link}>
                    Already have an account? Login
                </Link>
            </View>
        </View>
    );
}

const styles = StyleSheet.create({
    body: {
        flex: 1,
        backgroundColor: "#F2F2F2",
    },
    container: {
        flex: 1,
        justifyContent: "center",
        alignItems: "center",
        paddingHorizontal: 20,
    },
    title: {
        fontSize: 24,
        fontWeight: "bold",
        marginBottom: 20,
        color: "#333",
    },
    input: {
        width: Math.min(Dimensions.get("window").width * 0.7, 300),
        height: 40,
        borderWidth: 1,
        borderColor: "#ccc",
        borderRadius: 8,
        paddingHorizontal: 10,
        marginBottom: 15,
        backgroundColor: "#fff",
    },
    link: {
        marginTop: 15,
        color: "#007BFF",
        textDecorationLine: "underline",
    },
});
