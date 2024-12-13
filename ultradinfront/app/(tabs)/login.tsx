import NavBar from "@/components/ui/navbar"
import { TextInput, Button } from "react-native"
import { SafeAreaProvider, SafeAreaView } from "react-native-safe-area-context"
import React, { useState, useContext } from 'react';
import { API_URL } from "@/constants/Config"
import { AuthContext } from '../Contexts/AuthContext'; 
import { getValueFor, save } from '../../security/secureStorage.jsx'




export default function App() {
    const [email, setEmail] = useState("")
    const [password, setPassword] = useState("")

    const handleSubmit = async () => {
        try {
            const response = await fetch(API_URL + '/login', {
                method:"POST",
                headers:{
                    "Content-Type":"application/json"
                },
                body:JSON.stringify({email, password})
            })
            const data = await response.json()
            const token = data.token
            if (token) {
                save('jwt', token)
            } else {
                console.error('Token not found in response');
            }
        } catch (error) {
            console.error(error)
        }
    }

    const  getToken = async() => {
        try{
            getValueFor('jwt').then((token) => {
                console.log(token)
            })
        } catch (error) {
            console.error(error)

        }

    }

    return(
        <SafeAreaProvider>
            <NavBar/>
            <SafeAreaView>
                <TextInput 
                    placeholder="email" 
                    value={email}
                    onChangeText={setEmail}
                />
                <TextInput 
                    placeholder="Password" 
                    secureTextEntry 
                    value={password}
                    onChangeText={setPassword}
                />
                <Button title="Envoyer" onPress={handleSubmit}/>
                <Button title="Get Token" onPress={getToken}/>
            </SafeAreaView>
        </SafeAreaProvider>
    )
}
