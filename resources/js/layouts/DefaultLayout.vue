<template>
    <v-app>
        <v-navigation-drawer app v-model="drawer">
            <v-list dense>
                <v-list-item class="text-center">
                    <v-icon>mdi-view-dashboard</v-icon>
                    <v-list-item-title>Tableau de bord</v-list-item-title>
                </v-list-item>

                <v-list-item>
                    <v-row align="center" justify="space-between">
                        <v-col>
                            <v-tabs
                                v-model="tab"
                                background-color="transparent"
                                slider-color="primary"
                            >
                                <v-tab
                                    v-for="(tabItem, index) in tabs"
                                    :key="index"
                                >
                                    {{ tabItem.label }}
                                </v-tab>
                            </v-tabs>
                        </v-col>
                        <v-col class="text-center space-x-4">
                            <v-btn icon @click="prevTab"
                                ><v-icon>mdi-chevron-left</v-icon></v-btn
                            >
                            <v-btn icon @click="nextTab"
                                ><v-icon>mdi-chevron-right</v-icon></v-btn
                            >
                        </v-col>
                    </v-row>
                </v-list-item>

                <v-list-item v-if="tab !== null">
                    <v-list dense class="space-y-3">
                        <v-btn
                            v-for="(menuItem, index) in tabs[tab].menuItems"
                            :key="index"
                            :class="{
                                'menu-item-active': isActiveRoute(
                                    menuItem.route
                                ),
                            }"
                        >
                            <router-link :to="{ name: menuItem.route }" @click.stop="drawer = !drawer">
                                <v-row align="center">
                                    <v-col cols="auto">
                                        <v-icon>{{ menuItem.icon }}</v-icon>
                                    </v-col>
                                    <v-col cols="auto">
                                        <v-list-item-title>
                                            {{ menuItem.label }}
                                        </v-list-item-title>
                                    </v-col>
                                </v-row>
                            </router-link>
                        </v-btn>
                    </v-list>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <v-main>
            <v-app-bar app>
                <v-app-bar-nav-icon
                    @click.stop="drawer = !drawer"
                ></v-app-bar-nav-icon>
                <v-toolbar-title>{{ this.$route.name }}</v-toolbar-title>
            </v-app-bar>

            <v-container fluid>
                <v-row>
                    <v-col cols="12">
                        <RouterView />
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>

<script>
export default {
    data() {
        return {
            drawer: false,
            tabs: [
                {
                    label: "ERP",
                    menuItems: [
                        {
                            label: "ACCUEIL",
                            icon: "mdi-home",
                            route: "Accueil",
                        },
                        {
                            label: "PRODUITS",
                            icon: "mdi-home",
                            route: "Produits",
                        },
                        {
                            label: "FOURNISSEURS",
                            icon: "mdi-home",
                            route: "Fournisseurs",
                        },
                    ],
                },
                {
                    label: "CRM",
                    menuItems: [
                        {
                            label: "CONTACT",
                            icon: "mdi-help-circle",
                            route: "Contact",
                        },
                    ],
                },
            ],
            tab: 0,
            menuTitle: "",
        };
    },
    methods: {
        prevTab() {
            this.tab = (this.tab - 1 + this.tabs.length) % this.tabs.length;
        },
        nextTab() {
            this.tab = (this.tab + 1) % this.tabs.length;
        },
        isActiveRoute(routeName) {
            return this.$route.name === routeName;
        },
    },
};
</script>

<style scoped>
.v-slide-group__prev,
.v-slide-group__next {
    display: none !important;
}

.menu-item-active {
    background-color: lightgreen;
}
</style>
