<script setup lang="ts">
import { ref } from "vue";
import { useRouter } from "vue-router";
    
let username = ref('');
let password = ref('');
let email = ref('');
let router = useRouter();
 
let register = async () => {
  if (!isValidEmail(email.value)) {
    alert('Bitte geben Sie eine gültige E-Mail-Adresse ein!');
    return;
  }
  try {
    let response = await fetch('http://ivm108.informatik.htw-dresden.de/ewa/g08/backend/register.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: new URLSearchParams({
        email: email.value,
        username: username.value,
        password: password.value,
      }),
    });

    if (response.status === 200) {
      console.log('Registrierung erfolgreich');
      router.push('/');
      alert('Registrierung erfolgreich');
    }
    else if(response.status === 409){
      alert('User existiert bereits!');
      console.error('Fehler beim Registrieren: Username bereits vergeben');
    } 
    else if (response.status === 400) {
      console.error('email or username or password are empty or not set, no POST-Request');
      alert('email or username or password are empty or not set, no POST-Request');
      username.value = '';
      password.value = '';
      email.value = '';
    }
    else if (response.status === 500) {
      console.error('Server Error');
      alert('Server Error');
      username.value = '';
      password.value = '';
      email.value = '';
    }
  } catch (error) {
    console.error('Fehler bei der Anfrage:', error);}
};

function isValidEmail(email: string) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}
</script>

<template>
  <div class="flex">
    <div class="flex-box">
      <div class="login">
        <router-link to="/login">
          <img src="../../../images/back.png" alt="Go Back" class="back" />
        </router-link>
        <div class="login-header">
          Register
        </div>
        <input v-model="username" type="text" class="username form-control" placeholder="Username" required><br>
        <input v-model="password"  type="password" class="password form-control" placeholder="Password" required><br>
        <input v-model="email" @keydown.enter="register" type="text" class="username form-control" placeholder="E-Mail" required><br>
        <button @click="register" class="regbtn">Register</button>
      </div>
    </div>
  </div>
</template>
  
<style>
html{
  height: 100%;
  margin: 0;
  padding: 0;
  width: 100%;
}
.flex {
  display: flex;
  flex-direction: column;
  height: 100%;
}
.flex-box {
  display: flex;
  align-items: center;
  justify-content: center;
  flex: 1;
  flex-direction: column;
}
.login{
  background-color: white;
  padding: 20px;
  display: inline-block;
  max-width: 90%;
  box-sizing: border-box;
}
.login-header{
  font-size: 300%;
  font-weight: bold;
  padding-bottom: 20px;
  text-align: center;
  color: black;
}
input[type="text"], input[type="password"] {
  padding: 10px;
  line-height: 20px;
  margin: 0px 0px 10px;
  font-weight: bold;
  width: 200px;
  text-align: center;
  max-width: 100%;
  width: 3000px;
  box-sizing: border-box;
}
input[type="submit"] {
  background-color: white;
  padding: 50px;
  box-sizing: border-box;
  max-width: 100%;
  width: 3000px;
  font-weight: bold;
}
input[type="submit"]:hover {
  background-color: #440000;
  font-weight: bold;
  color: white;
}
.back {
  width: 25px;
  margin: auto;
  border-radius: 5px;
  border: 1px solid transparent; /* Setze eine transparente Border, damit die Transition funktioniert */
  transition: border 0.3s ease; /* Ändere die Transition-Zeit und füge eine Transition-Funktion hinzu */
}

.back:hover {
  border: 1px solid lightgray;
  border-radius: 5px;
}
.regbtn{
  margin-top: 20px;
  display: flex;
  flex-direction: column;
  width: 100%;
  background-color: rgb(33, 150, 243);
  color: white;
  cursor: pointer;
  border-radius: 5px;
  transition: 0.3s;
  height: 50px;
  font-size: 20px;
  display: flex;
  text-align: center;
  justify-content: center;
  align-items: center;
  font-weight: bold;
}
.regbtn:hover{
  background-color: rgb(14, 127, 219);
  font-weight: bold;
  color: white;
}
</style>