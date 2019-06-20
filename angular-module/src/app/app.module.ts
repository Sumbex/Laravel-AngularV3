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
import { CuentaBienestarComponent } from './auth-master/cuenta-bienestar/cuenta-bienestar.component';
import { FormularioBienestarComponent } from './auth-master/cuenta-bienestar/formulario-bienestar/formulario-bienestar.component';
import { TablaBienestarComponent } from './auth-master/cuenta-bienestar/tabla-bienestar/tabla-bienestar.component';
import { CuentaFondoMutuoComponent } from './auth-master/cuenta-fondo-mutuo/cuenta-fondo-mutuo.component';
import { FormularioFondoMutuoComponent } from './auth-master/cuenta-fondo-mutuo/formulario-fondo-mutuo/formulario-fondo-mutuo.component';
import { TablaFondoMutuoComponent } from './auth-master/cuenta-fondo-mutuo/tabla-fondo-mutuo/tabla-fondo-mutuo.component';
import { NoEncontradoComponent } from './no-encontrado/no-encontrado.component';

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
    CuentaBienestarComponent,
    FormularioBienestarComponent,
    TablaBienestarComponent,
    CuentaFondoMutuoComponent,
    FormularioFondoMutuoComponent,
    TablaFondoMutuoComponent,
    NoEncontradoComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    FormsModule,
    NgbModule
  ],
  providers: [
    UsuarioService
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
