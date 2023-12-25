<script setup lang="ts">
import { useRouter } from 'vue-router'

const router = useRouter()

let showUser = () => {
    router.push({name: 'AdminBenutzerVerwaltung'})
}
let showBooks = () => {
    router.push({name: 'AdminBuecherVerwaltung'})
}
let showStorage = () => {
    router.push({name: 'AdminLagerVerwaltung'})
}
let showOrders = () => {
    router.push({name: 'AdminBestellungenVerwaltung'})
}
</script>

<template>
    <div class="box">
        <div class="admin-meu">
            <div class="button-a" @click="showUser()">Benutzer Verwaltung</div>
            <div class="button-b" @click="showBooks()">Bücher hinzufügen</div>
            <div class="button-c" @click="showStorage()">Menge ändern</div>
            <div class="button-d" @click="showOrders()">Bestellungen verwalten</div>
        </div>
        <div class="daten-bearbeitung">
            <div class="e">Unter Menu</div>
        </div>
    </div>
</template>

<style scoped>
.box{
    margin: 100px auto;
    width: 50%;
    height: 50%;
    padding: 10px;
    border: 1px solid #ccc;
    background-color: #f3f3f3;
    border-radius: 5px;
    color: black;
}
.admin-meu{
    display: grid;
    grid-template-columns: repeat(4, 1fl);
    grid-template-rows: 50px;
}
.daten-bearbeitung{
    display: grid;
    grid-template-columns: repeat(4, 1fl);
    grid-template-rows: repeat(7, 50px);
}
.button-a{
    grid-column: 1;
    grid-row: 1;
    text-align: center;
    font-size: 30px;
    font-weight: bold;
}
.button-b{
    grid-column: 2;
    grid-row: 1;
    text-align: center;
    font-size: 30px;
    font-weight: bold;
}
.button-c{
    grid-column: 3;
    grid-row: 1;
    text-align: center;
    font-size: 30px;
    font-weight: bold;
}
.button-d{
    grid-column: 4;
    grid-row: 1;
    text-align: center;
    font-size: 30px;
    font-weight: bold;
}
.e{
    grid-column: 1 / span 4;
    grid-row: 1;
    text-align: center;
    font-size: 30px;
    font-weight: bold;
}
</style>