<template>
    <div class="container">
        <div class="row vertical-center">
            <div class="col text-center" v-show="!select">
                <div class="h3">Registration</div>

                <div class="card p-4 mx-auto mt-4" style="width: 300px;" v-on:keyup.enter="checkCredentials()">
                    <div class="form-group">
                        <label>Name</label>
                        <p class="text-danger" v-if="errors['first_name']">{{errors['first_name'][0]}}</p>
                        <p class="text-danger" v-if="errors[0]">{{errors}}</p>
                        <input v-model="newUser.first_name" class="form-control" type="text"   placeholder="имя">
                    </div>
                    <div class="form-group">
                        <label>Surname</label>
                        <p class="text-danger" v-if="errors['last_name']">{{errors['last_name'][0]}}</p>
                        <input v-model="newUser.last_name" class="form-control" type="text"  placeholder="фамилия">
                    </div>
                    <div class="form-group">
                        <label>Patronymic</label>
                        <p class="text-danger" v-if="errors['patronymic']">{{errors['patronymic'][0]}}</p>
                        <input v-model="newUser.patronymic" class="form-control" type="text"  placeholder="отчество">
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <p class="text-danger" v-if="errors['phone']">{{errors['phone'][0]}}</p>
                        <input v-model="newUser.phone" class="form-control" type="text"  v-mask="'# (###) ### ## ##'"  placeholder="8 (777) 777 77 77">
                    </div>
                    <div class="form-group">
                        <label>IIN</label>
                        <p class="text-danger" v-if="errors['iin']">{{errors['iin'][0]}}</p>
                        <input v-model="newUser.iin" class="form-control" type="text"  v-mask="'############'"  placeholder="IIN">
                    </div>
                    <div class="form-group">
                        <label>E-mail</label>
                        <p class="text-danger" v-if="errors['email']">{{errors['email'][0]}}</p>
                        <input v-model="newUser.email" class="form-control" type="text"  placeholder="e-mail">
                    </div>
                    <div class="form-group">
                        <button
                                class="btn btn-block btn-primary"
                                @click="sendForm"
                                :disabled="loading ? true : false"
                        >
                            <i v-show="loading" class="fa fa-spinner fa-spin"></i> Register
                        </button>
                    </div>
                    <router-link :to="{ name: 'login' }" class="text-muted">Already registered?</router-link>
                    <div class="help-block" v-if="error">{{ error.message }}</div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>

    import { post, get } from '../../helpers/api'

    export default {

        data() {
            return {
                loading: false,
                password: '',
                errors : [],
                id: '',
                select: false,
                accounts: [],
                newUser:{
                    first_name:'',
                    last_name:'',
                    patronymic:'',
                    phone: '',
                    iin:'',
                    email:''
                }
            }
        },
        methods: {
            sendForm(){
                let _this = this;
                post(
                    _this,
                    '/api/public/user/register',
                    {
                        user:_this.newUser
                    },
                    function(response){
                        _this.$router.push({name:'login'});
                    },
                    function(errors){
                        _this.errors = errors.response.data.errors;
                        console.log(_this.errors);
                    }
                );
            }
        },

        created() {

        }

    }

</script>