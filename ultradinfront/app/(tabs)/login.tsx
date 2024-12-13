import NavBar from "@/components/ui/navbar"
import { TextInput, Button } from "react-native"
import { SafeAreaProvider, SafeAreaView } from "react-native-safe-area-context"
import React, { useState } from "react"
import { API_URL } from "@/constants/Config"

export default function App() {
    const [username, setUsername] = useState("")
    const [password, setPassword] = useState("")

    const handleSubmit = async () => {
        try {
            const response = await fetch(API_URL + '/login', {
                method:"POST",
                headers:{
                    "Content-Type":"application/json"
                },
                body:JSON.stringify({username, password})
            })
            const data = await response.json()
            console.log(data)
        } catch (error) {
            console.error(error)
        }
    }

    return(
        <SafeAreaProvider>
            <NavBar/>
            <SafeAreaView>
                <TextInput 
                    placeholder="Username" 
                    value={username}
                    onChangeText={setUsername}
                />
                <TextInput 
                    placeholder="Password" 
                    secureTextEntry 
                    value={password}
                    onChangeText={setPassword}
                />
                <Button title="Envoyer" onPress={handleSubmit}/>
            </SafeAreaView>
        </SafeAreaProvider>
    )
}
