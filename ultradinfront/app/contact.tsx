import React from 'react';
import { StyleSheet, Text, View, Linking, TouchableOpacity } from 'react-native';
import Footer from "@/components/ui/footer";
import NavBar from "@/components/ui/navbar";

export default function Contact() {
    return(
        <View style={styles.body}>
        <NavBar />
        <Footer />
        </View>
    );
}

const styles = StyleSheet.create({
    body: {
        flex: 1,
        backgroundColor: "#F2F2F2",
    },
});