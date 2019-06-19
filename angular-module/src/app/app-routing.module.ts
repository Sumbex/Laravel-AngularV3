import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { PublicMasterComponent } from './public-master/public-master.component';
import { LoginComponent } from './public-master/login/login.component';
import { AboutComponent } from './public-master/about/about.component';
import { AuthMasterComponent } from './auth-master/auth-master.component';

const routes: Routes = [
  { path: '', component: PublicMasterComponent, children:[
      {path: '', component: LoginComponent},
      {path: 'about', component: AboutComponent}
  ]},

  { path: 'authMaster', component: AuthMasterComponent, children:[
    {path: '', component: LoginComponent},
    {path: 'about', component: AboutComponent}
]}
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
