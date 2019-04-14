<template>

    <div v-if="form">
        <b-modal v-if="data" ref="modal" size="lg" :title="title">

            <div  v-bind:class="{ 'has-error': errors && errors.role_id }" class="form-group row">
                <label class="col-3 col-form-label">Role</label>
                <div class="col-9">
                    <v-select :on-change="selectRole" label="name" value="id" :value.sync="form.role" :options="roles" placeholder="Choose Role"></v-select>
                    <form-error v-if="error_role" :errors="errors">
                        {{ error_role[0] }}
                    </form-error>
                </div>
            </div>

            <div v-bind:class="{ 'has-error': errors && errors.name }" class="form-group row">
                <label class="col-3 col-form-label">Name</label>
                <div class="col-9">
                    <input v-model="form.first_name" class="form-control" type="text" placeholder="Name">
                    <form-error v-if="errors && errors.errors && errors.errors.first_name" :errors="errors">
                        {{ errors.errors.first_name[0] }}
                    </form-error>
                </div>
            </div>
            <div v-bind:class="{ 'has-error': errors && errors.name }" class="form-group row">
                <label class="col-3 col-form-label">Surname</label>
                <div class="col-9">
                    <input v-model="form.last_name" class="form-control" type="text" placeholder="Surname">
                    <form-error v-if="errors && errors.errors && errors.errors.last_name" :errors="errors">
                        {{ errors.errors.last_name[0] }}
                    </form-error>
                </div>
            </div>
            <div v-bind:class="{ 'has-error': errors && errors.name }" class="form-group row">
                <label class="col-3 col-form-label">Patronymic</label>
                <div class="col-9">
                    <input v-model="form.patronymic" class="form-control" type="text" placeholder="Patronymic">
                    <form-error v-if="errors && errors.errors && errors.errors.patronymic" :errors="errors">
                        {{ errors.errors.patronymic[0] }}
                    </form-error>
                </div>
            </div>

            <div v-bind:class="{ 'has-error': errors && errors.phone }" class="form-group row">
                <label class="col-3 col-form-label">Phone</label>
                <div class="col-9">
                    <input v-model="form.phone" class="form-control" v-mask="'# (###) ### ## ##'" placeholder="8 (707) 465 48 12"/>
                    <form-error v-if="errors && errors.errors && errors.errors.phone" :errors="errors">
                        {{ errors.errors.phone[0] }}
                    </form-error>
                </div>
            </div>

            <div v-bind:class="{ 'has-error': errors && errors.iin }" class="form-group row">
                <label class="col-3 col-form-label">IIN</label>
                <div class="col-9">
                    <input v-model="form.iin" class="form-control" v-mask="'############'" placeholder="IIN"/>
                    <form-error v-if="errors && errors.errors && errors.errors.iin" :errors="errors">
                        {{ errors.errors.iin[0] }}
                    </form-error>
                </div>
            </div>

            <div v-bind:class="{ 'has-error': errors && errors.email }" class="form-group row">
                <label class="col-3 col-form-label">Email</label>
                <div class="col-9">
                    <input v-model="form.email" class="form-control" type="text" placeholder="email">
                    <form-error v-if="errors && errors.errors && errors.errors.email" :errors="errors">
                        {{ errors.errors.email[0] }}
                    </form-error>
                </div>
            </div>

            <div v-bind:class="{ 'has-error': errors && errors.password }" class="form-group row">
                <label class="col-3 col-form-label">Password</label>
                <div class="col-9">
                    <input v-model="form.password" class="form-control" type="text" placeholder="Password">
                </div>
            </div>

            <div v-bind:class="{ 'has-error': errors && errors.password_confirmation }" class="form-group row">
                <label class="col-3 col-form-label">Password(repeat)</label>
                <div class="col-9">
                    <input v-model="form.password_confirmation" class="form-control" type="text" placeholder="Password">
                    <form-error v-if="error_password" :errors="errors">
                        {{ error_password[0] }}
                    </form-error>
                </div>
            </div>
            <div class="row" v-if="!form.id"><div class="col-3"></div>
                <div class="col-9">
                    <button
                        type="button"
                        class="btn"
                        @click="randpass"
                        :disabled="formSending? true : false"
                >Generate Password</button>
                </div>
            </div>


            <div slot="modal-footer">
                <button
                        type="button"
                        class="btn btn-primary"
                        @click="sendForm"
                        :disabled="(formSending? true : false)"
                >
                    <i v-show="formSending" class="fa fa-spinner fa-spin"></i> {{ form.id ? 'Save' : 'Add' }}
                </button>
            </div>

        </b-modal>
    </div>


</template>



<script>

    import { post } from '../../helpers/api'

    export default {

        props: ['data', '_form'],

        data() {
            return {
                errors: [],
                error_password:[],
                error_role:[],
                formSending: false,
                form: '',
                title: ''
            }
        },
        computed: {
            roles() {
                var arr=[];
                if(this.$common.data) {
                    this.$common.data.roles.forEach(function(role) {
                        if(role.id != 3){
                            arr.push(role);
                        }
                    });
                }
                return arr;
            }
        },
        created() {
            this.form = this._form ? this._form : this.newUser();
        },
        mounted() {
            this.title = this.form.id ? 'Customize user' : 'Add user';
        },
        components: {
            FormError : require('./../../components/FormError.vue'),
            UserPhoto : require('./../../components/UserPhoto.vue'),
        },
        methods: {
            sendForm() {
                this.formSending = true;

                let _this = this;
                _this.error_role = [];
                _this.error_password = [];

                if(_this.form.role_id === ""){
                    _this.error_role.push("Choose Role");
                    _this.formSending = false;
                    return;
                }

                if((_this.form.password !== _this.form.password_confirmation) || (_this.form.password === "") || (_this.form.password_confirmation === "")){
                    _this.error_password.push("Password data incorrect");
                    _this.formSending = false;
                    return;
                }
                console.log(_this.form);

                post(_this, '/api/user-save', {
                    user: _this.form
                }, function () {

                    _this.formSending = false;
                    _this.errors = '';
                    _this.hideModal();
                    _this.form = _this.form.id ? _this.form : _this.newUser();
                    _this.$emit('formSending');


                }, function (error) {

                    _this.formSending = false;
                    _this.errors = error.response.data;
                    console.log(_this.errors.errors.email[0]);

                });


            },
            showModal() {
                this.$refs.modal.show();
            },
            hideModal() {
                this.$refs.modal.hide();
            },
            selectRole(val) {
                if (val)
                    this.form.role_id = val;
            },

            newUser() {

                return {
                    id:'',
                    first_name: '',
                    last_name: '',
                    patronymic: '',
                    phone: '',
                    iin:'',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    role: '',
                    role_id:''

                }

            },

            randpass() {
                var chars = "abcdefghijklmnopqrstuvwxyz1234567890";
                var pass = "";
                for (var x = 0; x < 8; x++) {
                    var i = Math.floor(Math.random() * chars.length);
                    pass += chars.charAt(i);
                }
                this.form.password=pass;
                this.form.password_confirmation=pass;
            }

        }



    }
</script>