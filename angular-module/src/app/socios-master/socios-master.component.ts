import { Component, OnInit } from '@angular/core';
import { UsuarioService } from '../servicios/usuarios.service';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-socios-master',
  templateUrl: './socios-master.component.html',
  styleUrls: ['./socios-master.component.css']
})
export class SociosMasterComponent implements OnInit {

  tiempoEsperaToken: number = 1;

  constructor(private _usuariosService: UsuarioService, private config: NgbModalConfig, private modalService: NgbModal) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }

  open(content) {
    this.modalService.open(content, {size: 'lg'});
  }

  startTimerToken(){
    setInterval(() => {
      if(this.tiempoEsperaToken > 0){
        this.tiempoEsperaToken--;
      }else{
        let estadoToken = this._usuariosService.isAuthenticated();
        if(estadoToken == false){
          window.location.reload();
        }else{
          this.tiempoEsperaToken = 1;
        }
      }
    },1000)
  }

}
