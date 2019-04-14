<template>

    <div class="sidebar container-fluid mt-4">
        <div class="row">
            <div class="col-2 bg-faded fixed-top h-100 pt-4 text-white bg-inverse small" style="z-index:3003">
                <div class="clearfix">
                    <div class="pull-left mr-2">
                        <div
                                :style="{
                                    'background-image': 'url(' + $root.user.getPhotoThumb() + ')',
                                    'background-size': 'cover',
                                    'width': '36px',
                                    'height': '36px',
                                    'display': 'block',
                                    'border-radius': '18px',
                                    '-webkit-border-radius': '18px',
                                    '-moz-border-radius': '18px'
                                }"

                        >
                        </div>
                    </div>
                    <div class="pull-left">
                        {{ $root.user.getName() }}
                        <div class="small text-muted">
                            {{ $root.user.getRoleDescription() }}
                        </div>
                    </div>
                </div>

                <hr>

                <ul>
                    <li class="mb-2" v-if="$root.user.data.role.id == 1"><router-link to="/dashboard" :class="{'text-muted': dashboard.indexOf($route.name) < 0,'text-white': dashboard.indexOf($route.name) > -1 }"><span class="fa fa-fw fa-bar-chart fa-lg"></span> Dashboard</router-link></li>
                    <li class="mb-2" v-if="$root.user.data.role.id == 1"><router-link to="/control" :class="{'text-muted': control.indexOf($route.name) < 0,'text-white': control.indexOf($route.name) > -1 }" ><span class="fa fa-fw fa-universal-access fa-lg"></span> Control</router-link></li>
                    <li class="mb-2" v-if="$root.user.data.role.id == 3"><router-link to="/histories" :class="{'text-muted': dashboard.indexOf($route.name) < 0,'text-white': dashboard.indexOf($route.name) > -1 }"><span class="fa fa-fw fa-heartbeat fa-lg"></span> Histories</router-link></li>
                    <li class="mb-2" v-if="$root.user.data.role.id == 3"><router-link to="/hospitals" :class="{'text-muted': control.indexOf($route.name) < 0,'text-white': control.indexOf($route.name) > -1 }" ><span class="fa fa-fw fa-hospital-o fa-lg"></span> Hospitals</router-link></li>
                    <li class="mb-2" v-if="$root.user.data.role.id == 2"><router-link to="/appointments" :class="{'text-muted': control.indexOf($route.name) < 0,'text-white': control.indexOf($route.name) > -1 }" ><span class="fa fa-fw fa-address-card fa-lg"></span> Appointments</router-link></li>
                </ul>
                <hr>
                <ul>
                    <li class="mb-2"><a @click="$refs.profile.showModal()" style="cursor: pointer;" class="text-muted" ><span class="fa fa-fw fa-cog fa-lg  mr-2"></span> My Settings</a></li>
                    <li class="mb-2"><div class="sidebar-logout"><button class="text-muted pl-0"  @click="logout"><span class="fa fa-fw fa-sign-out fa-lg mr-2"></span> Log out</button></div></li>
                </ul>

            </div>
        </div>
        <profile ref="profile" :_form="$root.user.data"></profile>
    </div>

</template>


<script>

    export default {

        data() {
            return {
                dashboard: [
                    'dashboard',
                ],
                control: [
                    'control',
                    'users',
                    'user',
                ]
            }
        },
        components: {
            Profile : require('./Profile.vue'),
        },
        methods: {

            getCsrf() {

                return Laravel.csrfToken;
            },

            logout() {


                if(this.$root.accounts.length > 1){

                    this.$router.push({name: 'select-account'});

                }else{
                    this.$auth.destroyToken();

                    this.$router.push({name: 'login'});

                }

                this.$root.user = '';



            }
        }


    }

</script>