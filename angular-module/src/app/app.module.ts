import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { LoginComponent } from './public-master/login/login.component';
import { UsuarioService } from './servicios/usuarios.service';
import { PublicMasterComponent } from './public-master/public-master.component';
import { AboutComponent } from './public-master/about/about.component';
import { AuthMasterComponent } from './auth-master/auth-master.component';
import { CuentaSindicalComponent } from './auth-master/cuenta-sindical/cuenta-sindical.component';
import { FormularioSindicalComponent } from './auth-master/cuenta-sindical/formulario-sindical/formulario-sindical.component';
import { TablaSindicalComponent } from './auth-master/cuenta-sindical/tabla-sindical/tabla-sindical.component';
import { NavbarComponent } from './navbar/navbar.component';
import { CuentaBienestarComponent } from './auth-master/cuenta-bienestar/cuenta-bienestar.component';
import { FormularioBienestarComponent } from './auth-master/cuenta-bienestar/formulario-bienestar/formulario-bienestar.component';
import { TablaBienestarComponent } from './auth-master/cuenta-bienestar/tabla-bienestar/tabla-bienestar.component';
import { CuentaFondoMutuoComponent } from './auth-master/cuenta-fondo-mutuo/cuenta-fondo-mutuo.component';
import { FormularioFondoMutuoComponent } from './auth-master/cuenta-fondo-mutuo/formulario-fondo-mutuo/formulario-fondo-mutuo.component';
import { TablaFondoMutuoComponent } from './auth-master/cuenta-fondo-mutuo/tabla-fondo-mutuo/tabla-fondo-mutuo.component';
import { NoEncontradoComponent } from './no-encontrado/no-encontrado.component';
import { AuthGuardService } from './guardianes/auth.guard.service';
import { JwtHelperService, JWT_OPTIONS  } from '@auth0/angular-jwt';
import { AniosService } from './servicios/anios.service';
import { TipoCuentasService } from './servicios/tipo-cuentas.service';
import { SindicalService } from './servicios/sindical.service';
import { ModalCajaChicaComponent } from './auth-master/modal-caja-chica/modal-caja-chica.component';
import { TablaCajaChicaComponent } from './auth-master/modal-caja-chica/tabla-caja-chica/tabla-caja-chica.component';
import { TotalCajaChicaComponent } from './auth-master/modal-caja-chica/total-caja-chica/total-caja-chica.component';
import { ModalInicioMesComponent } from './auth-master/modal-inicio-mes/modal-inicio-mes.component';
import { TablaInicioMesComponent } from './auth-master/modal-inicio-mes/tabla-inicio-mes/tabla-inicio-mes.component';
import { ConfiguracionesComponent } from './auth-master/configuraciones/configuraciones.component';

import { CambioContraseniaComponent } from './auth-master/configuraciones/cambio-contrasenia/cambio-contrasenia.component';

import { CajaChicaService } from './servicios/caja-chica.service';

import { TotalCuentaSindicalComponent } from './auth-master/cuenta-sindical/total-cuenta-sindical/total-cuenta-sindical.component';
import { CuentaSociosComponent } from './auth-master/cuenta-socios/cuenta-socios.component';
import { FormularioSociosComponent } from './auth-master/cuenta-socios/formulario-socios/formulario-socios.component';
import { ListarSociosComponent } from './auth-master/cuenta-socios/listar-socios/listar-socios.component';
import { ModalPrestamosSociosComponent } from './auth-master/modal-prestamos-socios/modal-prestamos-socios.component';
import { ModalCampingComponent } from './auth-master/modal-camping/modal-camping.component';
import { TablaPrestamosSociosComponent } from './auth-master/modal-prestamos-socios/tabla-prestamos-socios/tabla-prestamos-socios.component';
import { InteresPrestamoApuroEconomicoComponent } from './auth-master/modal-prestamos-socios/interes-prestamo-apuro-economico/interes-prestamo-apuro-economico.component';
import { BeneficiosSociosComponent } from './auth-master/cuenta-socios/beneficios-socios/beneficios-socios.component';
import { TablaPrestamosHistoricosSociosComponent } from './auth-master/modal-prestamos-socios/tabla-prestamos-historicos-socios/tabla-prestamos-historicos-socios.component';
import { TablasBeneficiosComponent } from './auth-master/cuenta-socios/tablas-beneficios/tablas-beneficios.component';
import { FormularioBeneficiosSocioComponent } from './auth-master/cuenta-socios/beneficios-socios/formulario-beneficios-socio/formulario-beneficios-socio.component';
import { FormularioBeneficiosBeneficiarioComponent } from './auth-master/cuenta-socios/beneficios-socios/formulario-beneficios-beneficiario/formulario-beneficios-beneficiario.component';
import { FormularioBeneficiosCargasComponent } from './auth-master/cuenta-socios/beneficios-socios/formulario-beneficios-cargas/formulario-beneficios-cargas.component';
import { FormularioBeneficiosConyugeComponent } from './auth-master/cuenta-socios/beneficios-socios/formulario-beneficios-conyuge/formulario-beneficios-conyuge.component';
import { FormularioBeneficiosPadresSuegrosComponent } from './auth-master/cuenta-socios/beneficios-socios/formulario-beneficios-padres-suegros/formulario-beneficios-padres-suegros.component';
import { TablaBeneficiosCargasComponent } from './auth-master/cuenta-socios/tablas-beneficios/tabla-beneficios-cargas/tabla-beneficios-cargas.component';
import { TablaBeneficiosConyugeComponent } from './auth-master/cuenta-socios/tablas-beneficios/tabla-beneficios-conyuge/tabla-beneficios-conyuge.component';
import { TablaBeneficiosPadresSuegrosComponent } from './auth-master/cuenta-socios/tablas-beneficios/tabla-beneficios-padres-suegros/tabla-beneficios-padres-suegros.component';
import { TablaBeneficiosSocioComponent } from './auth-master/cuenta-socios/tablas-beneficios/tabla-beneficios-socio/tabla-beneficios-socio.component';
import { SociosMasterComponent } from './socios-master/socios-master.component';
import { PerfilSocioComponent } from './socios-master/perfil-socio/perfil-socio.component';
import { NavbarSocioComponent } from './socios-master/navbar-socio/navbar-socio.component';
import { FormularioBeneficiosAuthSocioComponent } from './socios-master/perfil-socio/formulario-beneficios-auth-socio/formulario-beneficios-auth-socio.component';
import { TablaBeneficiosAuthSocioComponent } from './socios-master/perfil-socio/tabla-beneficios-auth-socio/tabla-beneficios-auth-socio.component';
import { FormularioBeneficiosConyugeAuthSocioComponent } from './socios-master/perfil-socio/formulario-beneficios-conyuge-auth-socio/formulario-beneficios-conyuge-auth-socio.component';
import { FormularioBeneficiosBeneficiarioAuthSocioComponent } from './socios-master/perfil-socio/formulario-beneficios-beneficiario-auth-socio/formulario-beneficios-beneficiario-auth-socio.component';
import { FormularioBeneficiosCargasAuthSocioComponent } from './socios-master/perfil-socio/formulario-beneficios-cargas-auth-socio/formulario-beneficios-cargas-auth-socio.component';
import { FormularioBeneficiosPadresSuegrosAuthSocioComponent } from './socios-master/perfil-socio/formulario-beneficios-padres-suegros-auth-socio/formulario-beneficios-padres-suegros-auth-socio.component';
import { PortalSociosService } from './servicios/portal-socios.service';
import { AuthGuardSocioService } from './guardianes/authSocio.guard.service';
import { TablaBeneficiosBeneficiarioComponent } from './auth-master/cuenta-socios/tablas-beneficios/tabla-beneficios-beneficiario/tabla-beneficios-beneficiario.component';
import { SafePipe } from './safe.pipe';
import { TablaResumenSocioComponent } from './auth-master/cuenta-socios/tablas-beneficios/tabla-resumen-socio/tabla-resumen-socio.component';
import { TablaBeneficiosAuthConyugeComponent } from './socios-master/perfil-socio/tabla-beneficios-auth-conyuge/tabla-beneficios-auth-conyuge.component';
import { TablaBeneficiosBeneficiarioAuthSocioComponent } from './socios-master/perfil-socio/tabla-beneficios-beneficiario-auth-socio/tabla-beneficios-beneficiario-auth-socio.component';
import { TablaBeneficiosCargasAuthSocioComponent } from './socios-master/perfil-socio/tabla-beneficios-cargas-auth-socio/tabla-beneficios-cargas-auth-socio.component';
import { TablaBeneficiosPadresSuegrosAuthSocioComponent } from './socios-master/perfil-socio/tabla-beneficios-padres-suegros-auth-socio/tabla-beneficios-padres-suegros-auth-socio.component';


@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    PublicMasterComponent,
    AboutComponent,
    AuthMasterComponent,
    CuentaSindicalComponent,
    FormularioSindicalComponent,
    TablaSindicalComponent,
    NavbarComponent,
    CuentaBienestarComponent,
    FormularioBienestarComponent,
    TablaBienestarComponent,
    CuentaFondoMutuoComponent,
    FormularioFondoMutuoComponent,
    TablaFondoMutuoComponent,
    NoEncontradoComponent,
    ModalCajaChicaComponent,
    TablaCajaChicaComponent,
    TotalCajaChicaComponent,
    ModalInicioMesComponent,
    TablaInicioMesComponent,
    ConfiguracionesComponent,

    CambioContraseniaComponent,

    TotalCuentaSindicalComponent,

    CuentaSociosComponent,

    FormularioSociosComponent,

    ListarSociosComponent,

    ModalPrestamosSociosComponent,

    ModalCampingComponent,

    TablaPrestamosSociosComponent,

    InteresPrestamoApuroEconomicoComponent,

    BeneficiosSociosComponent,

    TablaPrestamosHistoricosSociosComponent,

    TablasBeneficiosComponent,

    FormularioBeneficiosSocioComponent,

    FormularioBeneficiosBeneficiarioComponent,

    FormularioBeneficiosCargasComponent,

    FormularioBeneficiosConyugeComponent,

    FormularioBeneficiosPadresSuegrosComponent,

    TablaBeneficiosCargasComponent,

    TablaBeneficiosConyugeComponent,

    TablaBeneficiosPadresSuegrosComponent,

    TablaBeneficiosSocioComponent,
    TablaBeneficiosBeneficiarioComponent,

    SociosMasterComponent,

    PerfilSocioComponent,

    NavbarSocioComponent,

    FormularioBeneficiosAuthSocioComponent,

    TablaBeneficiosAuthSocioComponent,

    FormularioBeneficiosConyugeAuthSocioComponent,

    FormularioBeneficiosBeneficiarioAuthSocioComponent,

    FormularioBeneficiosCargasAuthSocioComponent,

    FormularioBeneficiosPadresSuegrosAuthSocioComponent,

    SafePipe,

    TablaResumenSocioComponent,

    TablaBeneficiosAuthConyugeComponent,

    TablaBeneficiosBeneficiarioAuthSocioComponent,

    TablaBeneficiosCargasAuthSocioComponent,

    TablaBeneficiosPadresSuegrosAuthSocioComponent

  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    FormsModule,
    NgbModule,
  ],
  providers: [
    UsuarioService,
    AuthGuardService,
    AuthGuardSocioService,
    { provide: JWT_OPTIONS, useValue: JWT_OPTIONS },
    JwtHelperService,
    AniosService,
    TipoCuentasService,
    SindicalService,
    CajaChicaService,
    PortalSociosService
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
