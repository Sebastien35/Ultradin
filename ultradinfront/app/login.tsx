import React, { useState } from "react";
import { TextInput, Button, StyleSheet, Text, View, Dimensions } from "react-native";
import { SafeAreaProvider, SafeAreaView } from "react-native-safe-area-context";
import NavBar from "@/components/ui/navbar";
import { API_URL } from "@/constants/Config";
import { getValueFor, save } from "../security/secureStorage.jsx";
import { Link } from "expo-router";

export default function Login() {
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");

    const handleSubmit = async () => {
        try {
            const response = await fetch(API_URL + "/login", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ email, password }),
            });
            const data = await response.json();
            const token = data.token;
            if (token) {
                save("jwt", token);
            } else {
                console.error("Token not found in response");
            }
        } catch (error) {
            console.error(error);
        }
    };

    const getToken = async () => {
        try {
            getValueFor("jwt").then((token) => {
                console.log(token);
            });
        } catch (error) {
            console.error(error);
        }
    };

    return (
        <SafeAreaProvider>
            <SafeAreaView style={styles.safeArea}>
                <NavBar />
                <View style={styles.container}>
                    <Text style={styles.title}>Hello</Text>
                    <TextInput
                        style={styles.input}
                        placeholder="Email"
                        value={email}
                        onChangeText={setEmail}
                    />
                    <TextInput
                        style={styles.input}
                        placeholder="Password"
                        secureTextEntry
                        value={password}
                        onChangeText={setPassword}
                    />
                    <Button title="Submit" onPress={handleSubmit} />
                    <Link href="register" style={styles.link}>
                        Register
                    </Link>
                    <View style={styles.spacer} />
                    <Button title="Debug" onPress={getToken} />
                </View>
            </SafeAreaView>
        </SafeAreaProvider>
    );
}

const styles = StyleSheet.create({
    safeArea: {
        flex: 1,
        backgroundColor: "#F2F2F2",
    },
    container: {
        flex: 1,
        justifyContent: "center",
        alignItems: "center",
        padding: 20,
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
    spacer: {
        height: 15,
    },
});
