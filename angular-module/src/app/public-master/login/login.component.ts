import { Component, OnInit } from '@angular/core';
import { Usuario } from '../../modelos/usuarios.model';
import { UsuarioService } from '../../servicios/usuarios.service';
import { Router } from '@angular/router';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {
  public usuario: Usuario;
  public status: string;
  public token: string;
  public loading: boolean = false;
  public noEncontrado = false;

  public lockLogin: boolean = false;

  constructor(private _userService: UsuarioService, private _portalSocios: PortalSociosService, private router: Router, config: NgbModalConfig, private modalService: NgbModal) {
    this.usuario = new Usuario();
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
    this.token = localStorage.getItem('token');
    if (this.token != null) {
      localStorage.clear();
    }
  }

  openLoginAdmins(Admins){
    this.modalService.open(Admins, { size: 'sm' });
  }

  onSubmit(form) {
    if(this.lockLogin == false){
    this.lockLogin = true;
    this._userService.login(this.usuario, true).subscribe(
      response => {
        if(response.status == 'success'){
        localStorage.setItem('rol', response.rol);
        this.token = response.token;
        localStorage.setItem('token', JSON.stringify(this.token));
        localStorage.setItem('usuario', JSON.stringify(this.usuario.email));
        this.lockLogin = false;
        this.router.navigate(['/AuthMaster']);
        console.log("estoy despues del navigate");
        document.getElementById("closeModalLogin").click();
      }else{
        this.lockLogin = false;
        this.noEncontrado = true;
        this.loading = false;
      }
      },
      error => {
        this.status = error;
        this.lockLogin = false;
        this.noEncontrado = true;
        this.loading = false;
      }
    );
    this.noEncontrado = false;
    this.loading = true;
  }
  }

  onSubmitSocios(form) {
    if(this.lockLogin == false){
    this.lockLogin = true;
    this._portalSocios.loginSocios(this.usuario).subscribe(
      response => {
        if(response.status == 'success'){
        localStorage.setItem('rol', response.rol);
        this.token = response.token;
        localStorage.setItem('token', JSON.stringify(this.token));
        localStorage.setItem('usuario', JSON.stringify(this.usuario.email));
        this.lockLogin = false;
        this.router.navigate(['/SociosMaster']);
        console.log("estoy despues del navigate");
        document.getElementById("closeModalLogin").click();
      }else{
        this.lockLogin = false;
        this.noEncontrado = true;
        this.loading = false;
      }
      },
      error => {
        this.status = error;
        this.lockLogin = false;
        this.noEncontrado = true;
        this.loading = false;
      }
    );
    this.noEncontrado = false;
    this.loading = true;
  }
  }

}
