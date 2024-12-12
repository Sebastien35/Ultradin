import { Link } from 'expo-router'
import React from 'react'
import {View, Text, StyleSheet, Button} from 'react-native'


export default function NavBar() {
    return(
        <View style={styles.navbar}>
            <Text style={styles.title}>My App</Text>
            <Link href='/login'>Login</Link>
        </View>
    )
}

const styles=StyleSheet.create({
    navbar:{
        height:60,
        backgroundColor:'#465569',
        justifyContent:'center',
        alignItems:'flex-start',
    },
    title:{
        color:'#fff',
        fontSize:20
    }
})
