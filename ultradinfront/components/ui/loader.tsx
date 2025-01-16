// Loader.tsx
import React, { useEffect, useRef } from 'react';
import { Animated, Easing, StyleSheet, View } from 'react-native';

export default function Loader() {
  const rotateValue = useRef(new Animated.Value(0)).current;

  useEffect(() => {
    // Lance une animation de rotation en boucle infinie
    Animated.loop(
      Animated.timing(rotateValue, {
        toValue: 1,
        duration: 1000,          // durée d'une rotation complète
        easing: Easing.linear,   // courbe linéaire pour une rotation fluide
        useNativeDriver: true,   // utiliser le driver natif pour de meilleures performances
      })
    ).start();
  }, [rotateValue]);

  // Interpolation de la valeur animée pour obtenir une rotation de 0° à 360°
  const spin = rotateValue.interpolate({
    inputRange: [0, 1],
    outputRange: ['0deg', '360deg'],
  });

  return (
    <View style={styles.container}>
      {/* Vue animée qui tourne pour créer un spinner */}
      <Animated.View style={[styles.spinner, { transform: [{ rotate: spin }] }]} />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    justifyContent: 'center',
    alignItems: 'center',
    flex: 1, // occupe tout l'espace disponible si nécessaire
  },
  spinner: {
    width: 50,
    height: 50,
    borderWidth: 5,
    borderColor: '#1E90FF',
    borderTopColor: 'transparent',   // rend la partie supérieure transparente pour créer l'effet de spinner
    borderRightColor: 'transparent', // rendre d'autres côtés transparents pour un effet en anneau
    borderBottomColor: 'transparent',
    borderRadius: 25,                // cercle parfait
  },
});
