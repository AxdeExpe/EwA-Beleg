<script setup lang="ts">
    import { ref } from "vue";
    import { useRouter } from "vue-router";
    import { updateIsloggedIn, updateIsAdmin, updatePassword, updateUsername  } from "@/store";
    
    let username = ref('');
    let password = ref('');
    let is_admin = ref(false);
    let router = useRouter();
    
    let doLogin = async () => {
      try {
        let response = await fetch('http://ivm108.informatik.htw-dresden.de/ewa/g08/backend/login.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: new URLSearchParams({
            username: username.value,
            password: password.value,
          }),
        });

        if (response.ok) {
          let data = await response.json();

          if(data.is_admin === '1'){
            updateIsAdmin(true);
            updateIsloggedIn(true);
            updatePassword(password.value);
            updateUsername(username.value);

            console.log(password.value);
            console.log(username.value);
            
            is_admin.value = true;
            router.push('/admin');
            console.log('Admin-login erfolgreich');
            alert('Admin-login erfolgreich');
          }
          else{
            updateIsAdmin(false);
            updateIsloggedIn(true);
            router.push('/katalog');
            console.log('Login erfolgreich');
            alert('Login erfolgreich');
          }
        } else if (response.status === 400) {
          console.error('username or password are empty or not set, no POST-Request');
          alert('username or password are empty or not set, no POST-Request');
          username.value = '';
          password.value = '';
        } else if (response.status === 401) {
          console.error('not authorized');
          alert('not authorized');
          username.value = '';
          password.value = '';
        } else if (response.status === 500) {
          console.error('Server Error');
          alert('Server Error');
          username.value = '';
          password.value = '';
        }
      } catch (error) {
        console.error('Fehler bei der Anfrage:', error);}
    };
</script>

<template>
    <div class="flex">
      <div class="flex-box">
        <div class="login">
          <div class="login-header">Sign in</div>
            <input v-model="username" type="text" class="username form-control" placeholder="Username" required><br>
            <input v-model="password" @keydown.enter="doLogin" type="password" class="password form-control" placeholder="Password" required><br>
            <button @click="doLogin" class="btn btn-primary logbtn">Login</button>
            <router-link to="/register" class="reg">
              <div>No Account?</div>
              <div>Register now!</div>
            </router-link>
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
  /* background-color: black; */
  height: 100%;
}
.flex-box {
  /* background-color: black; */
  display: flex;
  align-items: center;
  justify-content: center;
  flex: 1;
  flex-direction: column;
}
.login{
  padding: 20px;
  display: inline-block;
  max-width: 90%;
  box-sizing: border-box;
  height: 70%;
  width: 20%;

  min-width: 300px;

  margin: auto;
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

.register {
  color: blue;
  text-decoration: underline;
  cursor: pointer;
  display: flex;
  flex-direction: column;
  font-size: 120%;

  margin: auto;
  text-align: center;
}

.logbtn{
  margin: auto;
  display: flex;
  flex-direction: column;
  width: 100%;
  margin-top: 40px;
  margin-bottom: 15px;
  text-align: center;
  justify-content: center;
  align-items: center;
  height: 50px;
  font-size: 20px;
  font-weight: bold;
  background-color: rgb(33, 150, 243);
  color: white;
  border: none;
  cursor: pointer;
  border-radius: 5px;
  transition: 0.3s;
}

.logbtn:hover{
  background-color: rgb(14, 127, 219);
  font-weight: bold;
  color: white;
}

.reg{
  display: flex;
  flex-direction: column;
  text-decoration: none;
  font-size: 120%;
  color: rgb(14, 127, 219);
  margin: auto;
  text-align: center;
  justify-content: center;
  align-items: center;

  border: solid 1px rgb(180, 180, 180);
  border-radius: 5px;
  
  transition: 0.3s;
}

.reg:hover{
  background-color: rgba(180, 180, 180, 0.8);
  font-weight: bold;
}
</style>