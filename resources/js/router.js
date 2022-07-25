import vueRouter from 'vue-router';
import Vue from 'vue';
Vue.use(vueRouter);

import EmptyLayout from "./Layouts/EmptyLayout";
import AdminLayout from "./Layouts/AdminLayout";
import MagazinLayout from "./Layouts/MagazinLayout";
import Product_List from "./ProductViews/List";
import Product_New from "./ProductViews/New";
import Product_Edit from "./ProductViews/Edit";
import Country_List from "./CountryViews/List";
import Brands_List from "./BrandsViews/List";
import Index_Admin from "./AdminViews/Index";
import Users_List from "./UsersViews/List";
import Magazin_Index from "./MagazinView/Index";
import Magazin_Product from "./MagazinView/Product";
import Magazin_Favourites from "./MagazinView/Favourites";
import Magazin_Cart from "./MagazinView/Cart";
import Order_List from "./OrderViews/List";
import Magazin_About from "./MagazinView/About";
import Magazin_Help from "./MagazinView/Help";
import Magazin_Contact from "./MagazinView/Contact";
import Magazin_Payment from "./MagazinView/Payment";
import Magazin_Shipping from "./MagazinView/Shipping";
import Magazin_Privacy from "./MagazinView/Privacy";
import Magazin_Terms from "./MagazinView/Terms";

const router = new vueRouter({
  mode: "history",
  routes: [
    {
      path: '/', component: MagazinLayout, children: [
        { path: '', component: Magazin_Index, name: 'magazin.index' },
        { path: 'product/:id', component: Magazin_Product, name: 'magazin.product' },
        { path: 'favourites', component: Magazin_Favourites, name: 'magazin.favourites' },
        { path: 'cart', component: Magazin_Cart, name: 'magazin.cart' },
        { path: 'about', component: Magazin_About, name: 'magazin.about' },
        { path: 'help', component: Magazin_Help, name: 'magazin.help' },
        { path: 'contact', component: Magazin_Contact, name: 'magazin.contact' },
        { path: 'payment', component: Magazin_Payment, name: 'magazin.payment' },
        { path: 'shipping', component: Magazin_Shipping, name: 'magazin.shipping' },
        { path: 'privacy', component: Magazin_Privacy, name: 'magazin.privacy' },
        { path: 'terms', component: Magazin_Terms, name: 'magazin.terms' },
      ]
    },
    {
      path: '/admin', component: EmptyLayout, children: [
        { path: '', component: Index_Admin, name: 'admin.index' },
      ]
    },
    {
      path: '/admin/products', component: AdminLayout, children: [
        { path: '', component: Product_List, name: 'products.list' },
        { path: 'new', component: Product_New, name: 'products.new' },
        { path: 'edit/:id', component: Product_Edit, name: 'products.edit' },
        { path: 'countries', component: Country_List },
        { path: 'brands', component: Brands_List },
        { path: 'users', component: Users_List },
      ]
    },
    {
      path: '/admin/countries', component: AdminLayout, children: [
        { path: '', component: Country_List, name: 'countries.list' },
      ]
    },
    {
      path: '/admin/brands', component: AdminLayout, children: [
        { path: '', component: Brands_List, name: 'brands.list' },
      ]
    },
    {
      path: '/admin/users', component: AdminLayout, children: [
        { path: '', component: Users_List, name: 'users.list' },
      ]
    },
    {
      path: '/admin/orders', component: AdminLayout, children: [
        { path: '', component: Order_List, name: 'orders.list' },
      ]
    },
  ]
})

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('x_xsrf_token');
  if (!token) {
    if (to.name === 'admin.index') { return next() }
    else if (
      to.name != 'magazin.index' &&
      to.name != 'magazin.product' &&
      to.name != 'magazin.favourites' &&
      to.name != 'magazin.cart' &&
      to.name != 'magazin.about' &&
      to.name != 'magazin.help'&&
      to.name != 'magazin.contact' &&
      to.name != 'magazin.payment' &&
      to.name != 'magazin.shipping' &&
      to.name != 'magazin.privacy' &&
      to.name != 'magazin.terms'
    ) {
      return next({
        name: 'admin.index'
      })
    }
  }


  if (to.name == 'admin.index' && token) {
    return next({
      name: 'products.list'
    })
  }
  next()
})

export default router