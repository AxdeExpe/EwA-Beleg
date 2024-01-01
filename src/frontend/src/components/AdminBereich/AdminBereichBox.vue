<script setup lang="ts">
import { useRoute } from 'vue-router';

let route = useRoute()

let isLinkActive = (routePath: string) => {
  return route.path === routePath
}
</script>

<template>
    <div class="box">
        <div class="admin-meu">
            <RouterLink to="/admin/Bucheinfügen" :class="{ 'active': isLinkActive('/admin/Bucheinfügen') }" class="button-a">Bücher hinzufügen</RouterLink>
            <RouterLink to="/admin/Buchentfernen" :class="{ 'active': isLinkActive('/admin/Buchentfernen') }" class="button-b">Bücher entfernen</RouterLink>
            <RouterLink to="/admin/Katalogverwaltung" :class="{ 'active': isLinkActive('/admin/Katalogverwaltung') }" class="button-c">Katalog ändern</RouterLink>
            <RouterLink to="/admin/Bestellungsverwaltung" :class="{ 'active': isLinkActive('/admin/Bestellungsverwaltung') }" class="button-d">Bestellungen verwalten</RouterLink>
        </div>
        <slot name="Bucheinfügen"></slot>
        <slot name="Katalogverwaltung"></slot>
        
    </div>
</template>

<style scoped>
.active{
    text-decoration: underline;
}
.box{
    margin: 100px auto;
    width: 80%;
    height: 70%;
    padding: 10px;
    border: 1px solid #ccc;
    background-color: #f3f3f3;
    border-radius: 5px;
    color: black;
}
.admin-meu{
    display: grid;
    grid-template-columns: repeat(4, 1fl);
    grid-template-rows: 70px;
}

.button-a{
    display: grid;
    grid-column: 1;
    grid-row: 1;
    font-size: calc(.5em + 1vw);
    text-align: center;
    font-weight: bold;
    word-wrap: break-word;
}
.button-b{
    grid-column: 2;
    grid-row: 1;
    font-size: calc(.5em + 1vw);
    text-align: center;
    font-weight: bold;
    word-wrap: break-word;
}
.button-c{
    grid-column: 3;
    grid-row: 1;
    font-size: calc(.5em + 1vw);
    text-align: center;
    font-weight: bold;
    word-wrap: break-word;
}
.button-d{
    grid-column: 4;
    grid-row: 1;
    font-size: calc(.5em + 1vw);
    text-align: center;
    font-weight: bold;
    word-wrap: break-word;
}
</style>

<script lang="ts">
export default {
  name: 'AdminBereichBox',
}
</script>