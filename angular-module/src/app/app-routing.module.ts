import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { PublicMasterComponent } from './public-master/public-master.component';
import { LoginComponent } from './public-master/login/login.component';
import { AboutComponent } from './public-master/about/about.component';
import { AuthMasterComponent } from './auth-master/auth-master.component';
import { CuentaSindicalComponent } from './auth-master/cuenta-sindical/cuenta-sindical.component';

const routes: Routes = [
  { path: '', component: PublicMasterComponent, children:[
      {path: '', component: LoginComponent},
      {path: 'about', component: AboutComponent}
  ]},

  { path: 'AuthMaster', component: AuthMasterComponent, children:[
    { path: 'CuentaSindical', component: CuentaSindicalComponent },

]}
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
