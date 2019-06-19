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

@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    PublicMasterComponent,
    AboutComponent,
    AuthMasterComponent,
    CuentaSindicalComponent,
    FormularioSindicalComponent,
    TablaSindicalComponent
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
