import { NgModule, Component } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { PublicMasterComponent } from './public-master/public-master.component';
import { LoginComponent } from './public-master/login/login.component';
import { AboutComponent } from './public-master/about/about.component';
import { AuthMasterComponent } from './auth-master/auth-master.component';
import { CuentaSindicalComponent } from './auth-master/cuenta-sindical/cuenta-sindical.component';
import { CuentaBienestarComponent } from './auth-master/cuenta-bienestar/cuenta-bienestar.component';
import { CuentaFondoMutuoComponent } from './auth-master/cuenta-fondo-mutuo/cuenta-fondo-mutuo.component';
import { NoEncontradoComponent } from './no-encontrado/no-encontrado.component';
import { AuthGuardService } from './guardianes/auth.guard.service';
import { ConfiguracionesComponent } from './auth-master/configuraciones/configuraciones.component';
import { ListarSociosComponent } from './auth-master/cuenta-socios/listar-socios/listar-socios.component';
import { FormularioSociosComponent } from './auth-master/cuenta-socios/formulario-socios/formulario-socios.component';
import { TablaPrestamosSociosComponent } from './auth-master/modal-prestamos-socios/tabla-prestamos-socios/tabla-prestamos-socios.component';
import { BeneficiosSociosComponent } from './auth-master/cuenta-socios/beneficios-socios/beneficios-socios.component';

const routes: Routes = [
  { path: '', component: PublicMasterComponent, children:[
      { path: '', component: LoginComponent },
      { path: 'about', component: AboutComponent }
  ]},

  { path: 'AuthMaster', component: AuthMasterComponent, canActivate: [AuthGuardService], children:[
    { path: 'CuentaSindical', component: CuentaSindicalComponent },
    { path: 'CuentaBienestar', component: CuentaBienestarComponent },
    { path: 'CuentaFondoMutuo', component: CuentaFondoMutuoComponent },
    { path: 'Configuracion', component: ConfiguracionesComponent },
    { path: 'RegistroSocios', component: FormularioSociosComponent },
    { path: 'ListarSocios', component: ListarSociosComponent },
    { path: 'TablaPrestamoSocios', component: TablaPrestamosSociosComponent },
    { path: 'BeneficiosSocios', component: BeneficiosSociosComponent }

  ]},

  { path: '**', component: NoEncontradoComponent }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
