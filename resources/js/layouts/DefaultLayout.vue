<!-- default.vue -->
<template>
    <v-app id="inspire">
      <v-navigation-drawer class="pt-4" color="grey-lighten-3" >
        <v-avatar class="d-block text-center mx-auto mb-9">testtest</v-avatar>
        <v-list>
          <v-list-item v-for="link in links" :key="link.route">
            <router-link :to="{ name: link.route }">
              <v-avatar :prepend-icon="link.icon">
                {{ link.text }}
              </v-avatar>
            </router-link>
          </v-list-item>
        </v-list>
      </v-navigation-drawer>
  
      <v-main>
        <v-container>
          <RouterView/>
        </v-container>
      </v-main>
    </v-app>
  </template>
  
  <script setup>
  import { ref } from "vue";
  
  const links = ref([
    { text: "Home", route: "home", icon: "mdi-home" },
    { text: "Contact", route: "contact", icon: "mdi-help-circle" },
  ]);
  </script>
  