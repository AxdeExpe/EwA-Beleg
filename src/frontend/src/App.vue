<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router';
import { store, updateGesamtsumme, isloggedIn, updateIsloggedIn } from '@/store';

let gesamtPreis = () => {
  return store.value.warenkorb.reduce((total, item) => {
    return total + parseFloat(item.price_brutto) * item.quantity;
  }, 0).toFixed(2);
};

updateGesamtsumme();

let logOutAlert = () => {
  alert('Logout erfolgreich');
};
</script>

<template>
    <header>
      <nav>
        <div class="brand-container">
          <div class="brand">Buchshop</div>
        </div>
        <ul class="nav-container">
          <li><RouterLink to="/" class="nav-link">Home</RouterLink></li>
          <li><RouterLink to="/katalog" class="nav-link">Katalog</RouterLink></li>
          <li><RouterLink to="/about" class="nav-link">About</RouterLink></li>
        </ul>
        <div class="right-top">
          <ul class="warenkorb-container">
            <li class="punkt-entfernen">
              <RouterLink to="/warenkorb" class="nav-link">
                <img src="../../images/warenkorb.png" class="warenkorb_image"> ({{ gesamtPreis() }}€)
              </RouterLink>
            </li>
          </ul>
          <ul class="button-container">
            <div v-if="isloggedIn === true" class="login-button" @click="isloggedIn = false; updateIsloggedIn(false), logOutAlert()">
              <RouterLink to="/Login" class="login-button-text">Logout</RouterLink>
            </div>
            <div v-if="isloggedIn === false" class="login-button">
              <RouterLink to="/Login" class="login-button-text">Login</RouterLink>
            </div>
          </ul>
        </div>
      </nav>
    </header>
    <RouterView />
</template>

<style scoped>
.login-button{
  font-size: 150%;
  margin-right: 15%;
}
.login-button-text{
  text-decoration: none;
  color: white;
}
header {
  display: block;
  background-color: black;
  color: white;
  position: fixed;
  width: 100%;
  top: 0;
  z-index: 1;
} 
nav {
  display: flex;
  justify-content: space-between;
  align-items: center;   
  padding: 5px;
  font-size: 250%;     
  border: 2px solid white; 
  /* background-image: url(../../images/wallpaper.jpg); */

}
.brand {
  font-size: 1.5em;
  margin-right: 30px;
  padding: 0.1em;
  /* border: 2px solid red; */
}
.nav-container {
  display: flex;
  list-style: none;
  flex-grow: 1;
  padding: 0;  
  /* border: red 2px solid; */
}
.nav-link {
  margin: auto;
  margin-right: 1vw;
  text-decoration: none;
  color: white;
}
.right-top{
  display: flex;
  justify-content: center;
  align-items: center;
  margin: auto;
  position: absolute;
  right: 0;  
  /* border: 2px solid red; */
}
.button-container{
  display: flex;
  justify-content: flex-end;
  margin-right: 15px;
  list-style: none;
}
.warenkorb_image{
  min-width: 15%;
  max-width: 15%;
  position: relative;
  top: 5px;
}
.warenkorb-container{
  right: 5%;
  justify-content: flex-end;
  margin: auto;
  list-style: none;
}
</style>
