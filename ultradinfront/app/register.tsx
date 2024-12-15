import NavBar from "@/components/ui/navbar"
import { TextInput, Button } from "react-native"
import { SafeAreaProvider, SafeAreaView } from "react-native-safe-area-context"
import React, { useState, useContext } from 'react';
import { API_URL } from "@/constants/Config"
import { AuthContext } from '../Contexts/AuthContext'; 
import { getValueFor, save } from '../../security/secureStorage.jsx'




export default function App() {
    return(
        <SafeAreaProvider>
            <body style={styles.body}>
                <NavBar/>
                <h1>PAGE REGISTER</h1>
                <SafeAreaView>
                    <TextInput 
                        placeholder="email" 
                    />
                    <TextInput 
                        placeholder="Password" 
                        secureTextEntry 
                    />
                    <Button title="Envoyer"/>
                </SafeAreaView>
            </body>
        </SafeAreaProvider>
    )
}

const styles = {
    body: {
        fontFamily: "Arial, sans-serif", 
        backgroundColor: "#F2F2F2",
    },
};
