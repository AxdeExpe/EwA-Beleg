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
        <div class="brand">Buchshop</div>

        <div class="nav-container">
            <div><RouterLink to="/" class="nav-link">Home</RouterLink></div>
            <div><RouterLink to="/katalog" class="nav-link">Katalog</RouterLink></div>
            <div><RouterLink to="/about" class="nav-link">About</RouterLink></div>
        </div>        
        
        <div class="searchbar-container">
          <ul class="searchbar">
            <li class="punkt-entfernen">
              <input type="text" placeholder="Suche..." class="searchbar-input">
              <img src="../images/search-lens.png" class="search-button">
            </li>
          </ul>
        </div>

        <div class="right-top">
          <div class="warenkorb-container">
              <RouterLink to="/warenkorb" class="nav-link">
                <img src="../../images/warenkorb.png" class="warenkorb_image"> ({{ gesamtPreis() }}€)
              </RouterLink>
          </div>
          <div class="button-container">
            <div v-if="isloggedIn === true" class="nav-link" @click="isloggedIn = false; updateIsloggedIn(false), logOutAlert()">
              <RouterLink to="/Login" class="login-button-text">Logout</RouterLink>
            </div>
            <div v-if="isloggedIn === false" class="nav-link">
              <RouterLink to="/Login" class="login-button-text">Login</RouterLink>
            </div>
          </div>
        </div>
      </nav>
    </header>
    <RouterView />
</template>

<style scoped>
.login-button-text{
  text-decoration: none;
  color: white;
  font-size: 150%;
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
nav{
  display: flex;
  justify-content: space-between;
  align-items: center;   
  padding: 5px;
  font-size: 250%;     
  border: 2px solid white; 
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
.nav-link:hover {
  border-bottom: 2px solid #c278ff;
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

.searchbar-container{
  display: flex;
  position: fixed;
  margin: auto;
  justify-content: flex-start;

  height: 10%;
  width: 30%;

  right: 30%;
  
  border: 2px solid red;
  font-size: 1.5vh;
  text-decoration: none;
  color: white;
  background-color: black;
}

.searchbar{
  display: inline;
  height: 100%;
  list-style: none;
}

.searchbar-input{
  display: flex;

  margin: auto;

  height: 100%;
  width: 100%;
  border: 2px solid red;
  font-size: 1.5vh;
  text-decoration: none;
  color: white;
  background-color: black;

  border-radius: 50%;
  border: solid 1px lightgray;
}

.search-button{
  display: flex;
  justify-content: center;
  align-items: center;
  margin: auto;
  margin-left: 1vw;
  height: 100%;

  min-width: 15%;
  max-width: 15%;
  position: relative;


  border: 2px solid red;
}
</style>
