<template>
    <div class="col-8 offset-3">

        <table class="table mt-2">
            <thead class="thead-default">
            <tr>
                <th>Name</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="department in departments">
                <td>
                    {{department.name}}
                </td>
                <td>
                    <div class="pull-right">
                        <b-tooltip title="Open">
                            <router-link :to="{name:'department', params:{id: department.id}}" class="btn btn-outline-primary btn-sm"><span class="fa fa-hospital-o fa-2x"></span></router-link>
                        </b-tooltip>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>


<script>

    import { post, get } from '../../../helpers/api'

    export default {
        props:['id'],

        data() {
            return {
                departments:[],
                hospital:this.$route.params.id
            }
        },
        methods: {
        },

        mounted() {
            let _this = this;
            post(
                _this,
                '/api/hospitals/departments/get',
                {
                    hospital:_this.hospital
                },
                function(response){
                    _this.departments = response.data;
                },
                function(error){

                }
            );
        }

    }

</script>