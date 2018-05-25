const app_user = new Vue({
    el: '#app',
    data: {
        selected: [],
        dataUser: dataUser ? dataUser : [],
        page: 'user-register'
    },
    methods: {
        checkedSelectAll(){
            this.selectAll = !this.selectAll;
        },
        deleteUserRegister(){
            this.$refs['user-delete'].submit()
        },
        showModalConfirmDelete(event) {
            if(this.selected.length > 0){
                $(this.$refs.modal).modal('show');
            }
        },
        search(){
            this.$refs['user-search'].submit()
        }
    },
    computed: {
        selectAll: {
            get: function () {
                return this.dataUser ? this.selected.length == this.dataUser.length : false;
            },
            set: function (value) {
                var selected = [];
                if (value) {
                    this.dataUser.forEach(function (data, index) {
                        selected.push(data.id);
                    });
                }
                this.selected = selected;
            }
        }
    }

});
