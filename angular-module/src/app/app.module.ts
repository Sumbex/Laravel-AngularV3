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

    TablaPrestamosHistoricosSociosComponent

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
    { provide: JWT_OPTIONS, useValue: JWT_OPTIONS },
    JwtHelperService,
    AniosService,
    TipoCuentasService,
    SindicalService,
    CajaChicaService
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
