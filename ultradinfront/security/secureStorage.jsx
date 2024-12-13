import * as React from 'react';
import { Text, View, StyleSheet, TextInput, Button, Platform } from 'react-native';
import AsyncStorage from '@react-native-async-storage/async-storage';
import * as SecureStore from 'expo-secure-store';

export async function save(key, value) {
  try {
    if (Platform.OS === 'web') {
      await AsyncStorage.setItem(key, value);
    } else { // mobile
      await SecureStore.setItemAsync(key, value.toString());
    }
  } catch (error) {
    console.error("Error saving data:", error); 
  }
}

export async function getValueFor(key) {
  try {
    if (Platform.OS === 'web') {
      const result = await AsyncStorage.getItem(key);
      if (result) {
        alert("🔐 Here's your value 🔐 \n" + result);
      } else {
        alert('No values stored under that key.');
      }
    } else {
      const result = await SecureStore.getItemAsync(key);
      if (result) {
        alert("🔐 Here's your value 🔐 \n" + result);
      } else {
        alert('No values stored under that key.');
      }
    }
  } catch (error) {
    console.error("Error retrieving data:", error);
  }
}