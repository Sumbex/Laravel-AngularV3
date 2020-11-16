import { TestBed } from '@angular/core/testing';

import { ArchivadorBienestarService } from './archivador-bienestar.service';

describe('ArchivadorBienestarService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: ArchivadorBienestarService = TestBed.get(ArchivadorBienestarService);
    expect(service).toBeTruthy();
  });
});
