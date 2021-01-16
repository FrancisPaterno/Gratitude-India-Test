const api = ()=> import('./api')

export default{
    addCustomer(customer){
        return api().post('customer', customer);
    }
};