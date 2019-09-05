import { Component, OnInit, Input } from '@angular/core';

@Component({
  selector: 'app-nacimiento-bienestar',
  templateUrl: './nacimiento-bienestar.component.html',
  styleUrls: ['./nacimiento-bienestar.component.css']
})
export class NacimientoBienestarComponent implements OnInit {
  
  @Input () getIdSocio:'';
  @Input () getNombreSocio:'';

  constructor() {
    }

  ngOnInit() {
  }


}
