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
import { SociosMasterComponent } from './socios-master/socios-master.component';
import { PerfilSocioComponent } from './socios-master/perfil-socio/perfil-socio.component';
import { AuthGuardSocioService } from './guardianes/authSocio.guard.service';
import { BeneficiosSocioComponent } from './socios-master/beneficios-socio/beneficios-socio.component';
import { ConfiguracionSocioComponent } from './socios-master/configuracion-socio/configuracion-socio.component';
import { CuentasComponent } from './socios-master/cuentas/cuentas.component';
import { GraficoComponenteComponent } from './socios-master/grafico/grafico-componente/grafico-componente.component';
import { GeneralReunionComponent } from './socios-master/reuniones/general-reunion/general-reunion.component';
import { DetalleReunionComponent } from './socios-master/reuniones/detalle-reunion/detalle-reunion.component';
import { SecretariaComponent } from './auth-master/secretaria/secretaria.component';
import { MenuComponent } from './auth-master/liquidaciones/menu/menu.component';
import { CrearEmpleadoComponent } from './auth-master/liquidaciones/crear-empleado/crear-empleado.component';
import { TutorialMasterComponent } from './auth-master/tutorial-master/tutorial-master.component';
import { TutorialesSociosComponent } from './socios-master/tutoriales-socios/tutoriales-socios.component';
import { IndexComponent } from './auth-master/index/index.component';
import { AdminCumpleaniosComponent } from './auth-master/admin-cumpleanios/admin-cumpleanios.component';

const routes: Routes = [
  { path: '', component: PublicMasterComponent, children:[
      { path: '', component: LoginComponent },
      { path: 'about', component: AboutComponent }
  ]},

  { path: 'AuthMaster', component: AuthMasterComponent, canActivate: [AuthGuardService], children:[
    { path: 'CuentaSindical', component: CuentaSindicalComponent },
    { path: 'CuentaBienestar', component: CuentaBienestarComponent },
    { path: 'CuentaConsorcio', component: CuentaFondoMutuoComponent },
    { path: 'Configuracion', component: ConfiguracionesComponent },
    { path: 'RegistroSocios', component: FormularioSociosComponent },
    { path: 'ListarSocios', component: ListarSociosComponent },
    { path: 'TablaPrestamoSocios', component: TablaPrestamosSociosComponent },
    { path: 'BeneficiosSocios', component: BeneficiosSociosComponent },
    { path: 'Secretaria', component: SecretariaComponent },
    { path: 'Liquidaciones', component: MenuComponent },
    { path: 'CrearEmpleado', component: CrearEmpleadoComponent },
    { path: 'TutorialesMaster', component: TutorialMasterComponent },
    { path: 'Index', component:IndexComponent },
    { path: 'Cumpleanios', component:AdminCumpleaniosComponent }


  ]},

  { path: 'SociosMaster', component: SociosMasterComponent, canActivate: [AuthGuardSocioService], children:[
    { path: 'Perfil', component: PerfilSocioComponent },
    { path: 'Beneficios', component: BeneficiosSocioComponent },
    { path: 'Configuracion', component: ConfiguracionSocioComponent },
    { path: 'Cuentas', component: CuentasComponent },
    { path: 'Grafico', component: GraficoComponenteComponent },
    { path: 'Reuniones', component: GeneralReunionComponent },
    { path: 'TutorialesSocios', component: TutorialesSociosComponent }
  ]},

  { path: '**', component: NoEncontradoComponent }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
