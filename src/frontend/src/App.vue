<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router';
import { store, updateGesamtsumme } from '@/store';

let gesamtPreis = () => {
  return store.value.warenkorb.reduce((total, item) => {
    return total + parseFloat(item.price_brutto) * item.quantity;
  }, 0).toFixed(2);
};

updateGesamtsumme();
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
            <li class="punkt-entfernen"><button class="login-button"><RouterLink to="/Login"> Login</RouterLink></button></li>  
          </ul>
        </div>
      </nav>
    </header>
    <RouterView />
</template>

<style scoped>
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
  border: 2px solid red; 
  padding: 5px;       
}
.brand {
  font-size: 1.5em;
  margin-right: 30px;
  border: 2px solid red;
  padding: 0.1em;
}
.nav-container {
  display: flex;

  border: red 2px solid;
  list-style: none;
  flex-grow: 1;

  padding: 0;
}
.nav-link {
  margin: auto;
  margin-right: 1vw;
  text-decoration: none;
  color: white;
  font-size: 20px;
}

.right-top{
  display: flex;
  justify-content: center;
  align-items: center;
  border: 2px solid red;

  margin: auto;
  position: absolute;
  right: 0;
}

.button-container{
  display: flex;

  justify-content: flex-end;
  margin-right: 15px;

}
.warenkorb_image{

  min-width: 15%;
  max-width: 15%;
  position: relative;
  top: 5px;

}
.punkt-entfernen{
  list-style: none;
}
.login-button{
  text-decoration: none;
  color:black;
}
.warenkorb-container{

  right: 5%;
  justify-content: flex-end;
  margin: auto;

}

</style>
