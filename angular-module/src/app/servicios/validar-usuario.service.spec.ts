import { TestBed } from '@angular/core/testing';

import { ValidarUsuarioService } from './validar-usuario.service';

describe('ValidarUsuarioService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: ValidarUsuarioService = TestBed.get(ValidarUsuarioService);
    expect(service).toBeTruthy();
  });
});
