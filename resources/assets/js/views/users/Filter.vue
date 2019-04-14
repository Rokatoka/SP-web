<template>
    <!-- Поиск -->
    <div class="col-2 offset-2 fixed-top h-100 pt-4">
        <div class="form-group">
            <select class="form-control" v-model="filterData.field">
                <option value="name">Name</option>
                <option value="phone">Phone</option>
                <option value="email">Email</option>
            </select>
        </div>
        <div class="form-group" v-if="filterData.field != 'phone'">
            <input v-model="filterData.search_text" type="text" class="form-control form-control-sm" placeholder="Name, phone, email">
        </div>
        <div class="form-group" v-else>
            <input v-model="filterData.search_text" type="text" class="form-control form-control-sm" v-mask="'# (###) ### ## ##'">
        </div>

        <div class="form_group">
            <button @click="clearListLoad()" :disabled="load" class="btn btn-primary btn-block" >Apply</button>
            <button @click="resetFilter()" :disabled="load" class="btn btn-secondary btn-block btn-sm">Cancel Filter</button>
        </div>
    </div>
</template>

<script>
    export default {

        props: ['load'],

        data() {

            return {

                filterData: {
                    search_text: '',
                    field:''
                },
                temp: {
                },
            }

        },

        mounted(){
            this.$nextTick(function () {
                this.setFiltered(this.$route.query);
            });
        },

        methods: {
            resetFilter(){
                this.filterData.search_text = '';


                this.clearListLoad();
            },
            clearListLoad(){

                this.$nextTick(function () {
                    this.$emit('filtered');
                });

            },
            setFiltered(){

                this.$nextTick(function () {
                    this.setSelect();
                });


            },
            setSelect()
            {
                this.$nextTick(function(){
                    this.clearListLoad();
                })

            },
            selected(key, options, ids)
            {
                let comp = this;
                ids.forEach(function (id) {
                    options.forEach(function(value) {
                        if(value.id == id) comp.temp[key].push(value);
                    });
                });
            },
        }

    }
</script>